#!/usr/bin/env pwsh

param(
    [Parameter(Mandatory=$True)]
    [string] $Version
)

# Stop on every error
$script:ErrorActionPreference = 'Stop'

try {
    $DEST_DIR = 'dist'
    $PLUGIN_NAME = 'font-emoticons'

    if (-Not (Test-Path "$DEST_DIR/.svn")) {
        Write-Host -ForegroundColor Cyan "The directory '$DEST_DIR' is not an SVN working copy. Creating it."
        Write-Host

        & svn checkout "https://plugins.svn.wordpress.org/$PLUGIN_NAME/trunk" $DEST_DIR
        if (-Not $?) {
            throw '"svn checkout" failed'
        }
    }
    else {
        & svn update $DEST_DIR
        if (-Not $?) {
            throw '"svn update" failed'
        }
    }

    & rsync --recursive --human-readable --times --delete --exclude=.svn 'src/' "$DEST_DIR/"
    if (-Not $?) {
        throw '"rsync" failed'
    }

    Copy-Item './license.txt' $DEST_DIR

    # Create release zip
    New-Item dist-zip -ItemType Directory -ErrorAction SilentlyContinue | Out-Null
    Get-ChildItem $DEST_DIR -Exclude '.svn' | Compress-Archive -DestinationPath "dist-zip/$PLUGIN_NAME-$Version.zip"
}
catch {
    # IMPORTANT: We compare type names(!) here - not actual types. This is important because - for example -
    #   the type 'Microsoft.PowerShell.Commands.WriteErrorException' is not always available (most likely
    #   when Write-Error has never been called).
    if ($_.Exception.GetType().FullName -eq 'Microsoft.PowerShell.Commands.WriteErrorException') {
        # Print error messages (without stacktrace)
        Write-Host -ForegroundColor Red $_.Exception.Message
    }
    else {
        # Print proper exception message (including stack trace)
        # NOTE: We can't create a catch block for "RuntimeException" as every exception
        #   seems to be interpreted as RuntimeException.
        if ($_.Exception.GetType().FullName -eq 'System.Management.Automation.RuntimeException') {
            Write-Host -ForegroundColor Red $_.Exception.Message
        }
        else {
            Write-Host -ForegroundColor Red "$($_.Exception.GetType().Name): $($_.Exception.Message)"
        }
        Write-Host -ForegroundColor Red $_.ScriptStackTrace
    }

    exit 1
}
