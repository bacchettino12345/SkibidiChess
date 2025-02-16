function isDesktopSite() {

    if(/mobile/i.test(navigator.userAgent))
    {
        document.getElementById("desktopRequiredMessage").style.display = "flex"
    }
    else
    {
        document.getElementById("desktopRequiredMessage").style.visibility = "hidden"
    }
  
    return /mobile/i.test(navigator.userAgent) ? "none": "initial"
  };

  isDesktopSite();