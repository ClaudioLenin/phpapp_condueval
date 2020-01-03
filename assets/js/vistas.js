$(document).ready(function(){
   $('ul.tabs li a:first').addClass('activo');
   $('.secciones article').hide();
   $('.secciones article:first').show();
   
   $('ul.tabs li a').click(function(){
       $('ul.tabs li a').removeClass('activo');
       $(this).addClass('activo');
       $('.secciones article').hide();
       var activeTab = $(this).attr('href');
       $(activeTab).show();
       return false;
   });
});
