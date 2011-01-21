<?php
 $browser_images = array(
        "Akregator" => "akregator.png",
        "Amiga" => "amigavoyager.png",
        "Avant Browser" => "avant.png",
        "Chimera" => "chimera.png",
        "Chrome" => "chrome.png",
        "Dillo" => "dillo.png",
        "eCatch" => "ecatch.png",
        "Enigma Browser" => "epiphany.png",
        "FeedDemon" => "feeddemon.png",
        "Firefox" => "firefox.png",
        "Firefox Mobile" => "firefox.png",
        "FlashGet" => "flashget.png",
        "FreshDownload" => "freshdownload.png",
        "FrontPage" => "frontpage.png",
        "Galeon" => "galeon.png",
        "GetRight" => "getright.png",
        "GetRightPro" => "getright.png",
        "gnome-vfs" => "gnome.png",
        "GoZilla" => "gozilla.png",
        "HTTrack" => "httrack.png",
        "IBrowse" => "ibrowse.png",
        "iCab" => "icab.png",
        "K-Meleon" => "kmeleon.png",
        "Konqueror" => "konqueror.png",
        "LeechGet" => "leechget.png",
        "Lynx" => "lynx.png",
        "Media Players" => "mediaplayer.png",
        "Motorola Internet Browser" => "motorola.png",
        "Mozilla" => "mozilla.png",
        "MPlayer" => "mplayer.png",
        "IE" => "msie.png",
        "IE Offline Browser" => "msie.png",
        "IECheck" => "msie.png",
        "IEMobile" => "msie.png",
        "Mosaic" => "ncsa_mosaic.png",
        "NetNewsWire" => "netnewswire.png",
        "Netscape" => "netscape.png",
        "NewsFire" => "newsfire.png",
        "NewsGator" => "newsgator.png",
        "Newz Crawler" => "newzcrawler.png",
        "Nokia" => "nokia.png",
        "OmniWeb" => "omniweb.png",
        "Opera" => "opera.png",
        "Opera Mini" => "opera.png",
        "Opera Mobi" => "opera.png",
        "Phoenix" => "phoenix.png",
        "RealDownload" => "real.png",
        "RealPlayer" => "real.png",
        "RSS Reader Panel" => "rssreader.png",
        "Safari" => "safari.png",
        "Safari RSS" => "safari.png",
        "SafariBookmarkChecker" => "safari.png",
        "SharpReader" => "sharpreader.png",
        "Sony PS2" => "sony.png",
        "SonyEricsson" => "sony.png",
        "Teleport" => "teleport.png",
        "W3C Line Mode" => "w3c.png",
        "W3C Link Checker" => "w3c.png",
        "W3C Validator" => "w3c.png",
        "W3CRobot" => "w3c.png",
        "W3C-WebCon" => "w3c.png",
        "WebCopier" => "webcopier.png",
        "WebReaper" => "webreaper.png",
        "WebTV/MSNTV" => "webtv.png",
        "WebZIP" => "webzip.png",
        "Wizz" => "wizz.png"
  );

  $os_images = array(
        "AIX" => "aix.png",
        "Amiga" => "amigaos.png",
        "BeOS" => "beos.png",
        "Darwin" => "macosx.png",
        "Debian" => "debian.png",
        "Digital Unix" => "digital.png",
        "FreeBSD" => "freebsd.png",
        "HP-UX" => "hpux.png",
        "iPhone OSX" => "macosx.png",
        "IRIX" => "irix.png",
        "IRIX64" => "irix.png",
        "JAVA" => "java.png",
        "Linux" => "linux.png",
        "Mac68K" => "mac.png",
        "MacOSX" => "macosx.png",
        "MacPPC" => "macintosh.png",
        "NetBSD" => "netbsd.png",
        "OpenBSD" => "openbsd.png",
        "OS/2" => "os2.png",
        "RISC OS" => "riscos.png",
        "Solaris" => "sunos.png",
        "SunOS" => "sunos.png",
        "SymbianOS" => "symbian.png",
        "Unix" => "unix.png",
        "WebTV" => "webtv.png",
        "Win16" => "win16.png",
        "Win2000" => "win2000.png",
        "Win2003" => "win2003.png",
        "Win31" => "win.png",
        "Win32" => "win.png",
        "Win7" => "win.png",
        "Win95" => "win95.png",
        "Win98" => "win98.png",
        "WinCE" => "wince.png",
        "WinME" => "winme.png",
        "WinNT" => "winnt.png",
        "WinVista" => "win.png",
        "WinXP" => "winxp.png"
  );

function prli_browser_image($browser)
{
  global $browser_images;

  $image = $browser_images[$browser];
  
  if(empty($image))
    $image = "unknown.png";

  return $image;
}

function prli_os_image($os)
{
  global $os_images;

  $image = $os_images[$os];
  
  if(empty($image))
    $image = "unknown.png";

  return $image;
}

?>
