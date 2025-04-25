// Header Active
$(window).scroll(function () {
  var scroll = $(window).scrollTop();
  if (scroll > 0) {
    $("#header").addClass("active");
  } else {
    $("#header").removeClass("active");
  }
});
// Header Active

// AOS
AOS.init();
// AOS

// Side Nav
function openNav() {
  document.getElementById("mySidenav").style.width = "100%";
}

function closeNav() {
  document.getElementById("mySidenav").style.width = "0";
}
// Side Nav

// Component creator
let includes = document.getElementsByTagName("include");
for (var i = 0; i < includes.length; i++) {
  let include = includes[i];
  load_file(includes[i].attributes.src.value, function (text) {
    include.insertAdjacentHTML("afterend", text);
    include.remove();
  });
}
function load_file(filename, callback) {
  fetch(filename)
    .then((response) => response.text())
    .then((text) => callback(text));
}
// <include src="header.html"></include>
// Component creator

// Sidebar collapse
document.addEventListener("DOMContentLoaded", function () {
  const navLinks = document.querySelectorAll(
    '.nav-link[data-bs-toggle="collapse"]'
  );

  navLinks.forEach((link) => {
    const collapseTargetId = link.getAttribute("href")?.replace("#", "");
    const collapseEl = document.getElementById(collapseTargetId);
    const arrow = link.querySelector(".collapse-arrow");

    if (!collapseEl || !arrow) return;

    collapseEl.addEventListener("show.bs.collapse", () => {
      arrow.classList.add("rotate");
    });

    collapseEl.addEventListener("hide.bs.collapse", () => {
      arrow.classList.remove("rotate");
    });
  });
});
// Sidebar collapse
