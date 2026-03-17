// $(".menu > ul > li").click(function (e) {
//     // remove active from already active
//     $(this).siblings().removeClass("active");
//     // add active to clicked
//     $(this).toggleClass("active");
//     // if has sub menu open it
//     $(this).find("ul").slideToggle();
//     // close other sub menu if any open
//     $(this).siblings().find("ul").slideUp();
//     // remove active class of sub menu items
//     $(this).siblings().find("ul").find("li").removeClass("active");
//   });
  
//   $(".menu-btn").click(function () {
//     $(".sidebar").toggleClass("active");
//   });
  

// perfil.js

document.addEventListener('DOMContentLoaded', function () {
  const fileInput = document.getElementById('file-input');
  const profilePic = document.getElementById('profile-pic');

  fileInput.addEventListener('change', function (event) {
      const file = event.target.files[0];
      if (file) {
          const reader = new FileReader();
          reader.onload = function (e) {
              profilePic.src = e.target.result;
          }
          reader.readAsDataURL(file);
      }
  });
});
