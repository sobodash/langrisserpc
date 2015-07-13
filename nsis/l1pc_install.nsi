;NSIS Modern User Interface version 1.63
;Langrisser I Installer Script

!define MUI_PRODUCT "Langrisser I" ;Define your own software name here
!define MUI_VERSION "1.0en" ;Define your own software version here

!include "MUI.nsh"
  
;--------------------------------
;Configuration

  OutFile "Setup.exe"

  InstallDir "$PROGRAMFILES\SoftAction\${MUI_PRODUCT}"
  InstallDirRegKey HKCU "Software\SoftAction\${MUI_PRODUCT}" ""

  ;Remember the Start Menu Folder
  !define MUI_STARTMENUPAGE_REGISTRY_ROOT "HKCU" 
  !define MUI_STARTMENUPAGE_REGISTRY_KEY "Software\${MUI_PRODUCT}" 
  !define MUI_STARTMENUPAGE_REGISTRY_VALUENAME "Start Menu Folder"

  !define TEMP $R0

;--------------------------------
;Modern UI Configuration

  !define MUI_ICON "${NSISDIR}\Contrib\Icons\setup.ico"
  !define MUI_UNICON "${NSISDIR}\Contrib\Icons\normal-uninstall.ico"

  !define MUI_WELCOMEPAGE
    !define MUI_SPECIALBITMAP "${NSISDIR}\Contrib\Icons\modern-wizard langrisser.bmp"
  !define MUI_LICENSEPAGE
    !define MUI_HEADERBITMAP "${NSISDIR}\Contrib\Icons\modern-header langrisser.bmp"
; !define MUI_COMPONENTSPAGE
  !define MUI_DIRECTORYPAGE
  !define MUI_STARTMENUPAGE
  !define MUI_FINISHPAGE
    !define MUI_FINISHPAGE_RUN "$INSTDIR\langpc.exe"
  
  !define MUI_ABORTWARNING
  
  !define MUI_UNINSTALLER
  !define MUI_UNCONFIRMPAGE
  
;--------------------------------
;Languages
 
  !insertmacro MUI_LANGUAGE "English"
  
;--------------------------------
;Language Strings

  ;Description
  LangString DESC_SecCopyUI ${LANG_ENGLISH} "Install Langrisser I."

;--------------------------------
;Data
  
  LicenseData "${NSISDIR}\Contrib\Modern UI\License - Langrisser.txt"

;--------------------------------
;Installer Sections

Section "Setup.exe" SecCopyUI

  SetOutPath "$INSTDIR"
  File "C:\Translations\l1pc\SoftAction\Langrisser I\FACE.RES"
  File "C:\Translations\l1pc\SoftAction\Langrisser I\SCENDAT.RES"
  File "C:\Translations\l1pc\SoftAction\Langrisser I\SPR.RES"
  File "C:\Translations\l1pc\SoftAction\Langrisser I\tdat.res"
  File "C:\Translations\l1pc\SoftAction\Langrisser I\save00.dat"
  File "C:\Translations\l1pc\SoftAction\Langrisser I\save01.dat"
  File "C:\Translations\l1pc\SoftAction\Langrisser I\save02.dat"
  File "C:\Translations\l1pc\SoftAction\Langrisser I\save03.dat"
  File "C:\Translations\l1pc\SoftAction\Langrisser I\staff.mes"
  File "C:\Translations\l1pc\SoftAction\Langrisser I\langpc.exe"
  File "C:\Translations\l1pc\SoftAction\Langrisser I\manual.pdf"
  SetOutPath "$INSTDIR\resfs"
  File "C:\Translations\l1pc\SoftAction\Langrisser I\resfs\SHOP.pct"
  SetOutPath "$WINDIR\Fonts"
  File "C:\Translations\l1pc\SoftAction\Langrisser I\data\LANG.FON"
  SetOutPath "$INSTDIR"
    
  ;Write registry values
  WriteRegStr HKCU "Software\Microsoft\Windows NT\CurrentVersion\AppCompatFlags\Layers" "$INSTDIR\langpc.exe" "WIN98 DISABLECICERO"
  
  fonts::registerFont "$WINDIR\Fonts\LANG.FON"

  ;Store install folder
  WriteRegStr HKCU "Software\SoftAction\${MUI_PRODUCT}" "" $INSTDIR
  
  !insertmacro MUI_STARTMENU_WRITE_BEGIN
    
    ;Create shortcuts
    CreateDirectory "$SMPROGRAMS\${MUI_STARTMENUPAGE_VARIABLE}"
    CreateShortCut "$SMPROGRAMS\${MUI_STARTMENUPAGE_VARIABLE}\Langrisser I.lnk" "$INSTDIR\langpc.exe"
    CreateShortCut "$SMPROGRAMS\${MUI_STARTMENUPAGE_VARIABLE}\Instruction Manual.lnk" "$INSTDIR\manual.pdf"
    CreateShortCut "$SMPROGRAMS\${MUI_STARTMENUPAGE_VARIABLE}\Release Notes.lnk" "$INSTDIR\release.txt"
    CreateShortCut "$SMPROGRAMS\${MUI_STARTMENUPAGE_VARIABLE}\Uninstall.lnk" "$INSTDIR\Uninstall.exe"
  
  !insertmacro MUI_STARTMENU_WRITE_END
  
  CreateShortcut "$DESKTOP\Langrisser I.lnk" $INSTDIR\langpc.exe
  
  ;Create uninstaller
  WriteUninstaller "$INSTDIR\Uninstall.exe"

SectionEnd

;--------------------------------
;Descriptions

!insertmacro MUI_FUNCTIONS_DESCRIPTION_BEGIN
  !insertmacro MUI_DESCRIPTION_TEXT ${SecCopyUI} $(DESC_SecCopyUI)
!insertmacro MUI_FUNCTIONS_DESCRIPTION_END

;--------------------------------
;Uninstaller Section

Section "Uninstall"

  ;Add your stuff here

  Delete "$INSTDIR\FACE.RES"
  Delete "$INSTDIR\SCENDAT.RES"
  Delete "$INSTDIR\SPR.RES"
  Delete "$INSTDIR\tdat.res"
  Delete "$INSTDIR\save00.dat"
  Delete "$INSTDIR\save01.dat"
  Delete "$INSTDIR\save02.dat"
  Delete "$INSTDIR\save03.dat"
  Delete "$INSTDIR\staff.mes"
  Delete "$INSTDIR\langpc.exe"
  Delete "$INSTDIR\manual.pdf"
  Delete "$INSTDIR\resfs\SHOP.pct"
  Delete "$WINDIR\Fonts\LANG.FON"
  Delete "$INSTDIR\Uninstall.exe"
  
  ;Remove shortcut
  ReadRegStr ${TEMP} "${MUI_STARTMENUPAGE_REGISTRY_ROOT}" "${MUI_STARTMENUPAGE_REGISTRY_KEY}" "${MUI_STARTMENUPAGE_REGISTRY_VALUENAME}"
  
  StrCmp ${TEMP} "" noshortcuts
  
    Delete "$SMPROGRAMS\${TEMP}\Langrisser I.lnk"
    Delete "$SMPROGRAMS\${TEMP}\Instruction Manual.lnk"
    Delete "$SMPROGRAMS\${TEMP}\Release Notes.lnk"
    Delete "$SMPROGRAMS\${TEMP}\Uninstall.lnk"
    RMDir "$SMPROGRAMS\${TEMP}" ;Only if empty, so it won't delete other shortcuts
    
  noshortcuts:

  RMDir "$INSTDIR\resfs"
  RMDir "$INSTDIR\data"
  RMDir "$INSTDIR"

  DeleteRegValue HKCU "Software\Microsoft\Windows NT\CurrentVersion\AppCompatFlags\Layers" "$INSTDIR\langpc.exe"
  
  fonts::unregisterFont "$WINDIR\Fonts\LANG.FON"
  
  DeleteRegKey /ifempty HKCU "Software\SoftAction\${MUI_PRODUCT}"
  Delete "$DESKTOP\Langrisser I.lnk"
  
  !insertmacro MUI_UNFINISHHEADER

SectionEnd