'use strict';

(function ($) {
  
  $(document).ready(function () {
    // Téléverser une image
    $('#capture').on('click', function(e){
      e.preventDefault();
      $('#upload').trigger('click');
    });
    
    // Bloquer ou débloquer un commentaire
    function moderationComment(membreId, commentId, action){
      $.ajax({
        url: '../modererCommentaire.php',
        type: 'POST',
        data: {'id_membre':membreId, 'id_comment':commentId, 'publie':action},
        dataType: 'html',
        /*complete: function(resultat, status) {
          console.log(resultat);
        },*/
        success: function(data, status) {
          console.log(resultat);
          var resultat = data;
          if(resultat.split('_')[1] == 'oui'){
            $('#'+resultat.split('_')[0]).html('<i class="published">Commentaire validé et publié</i>');
          } else if(resultat.split('_')[1] == 'non'){
            $('#'+resultat.split('_')[0]).html('<i class="unpublished">Commentaire à valider</i>');
          }
        },
        error: function(data, status, err) {
          console.log(err);
        }
      });
    }

    $('.lock-unlock').each(function(){
      $(this).on('click',function(e) {
        e.preventDefault();
        var membre_comment = $(this).data('comment-info');
        var id_membre = membre_comment.split('_')[0];
        var id_comment = membre_comment.split('_')[1];

        //console.log(id_membre);
        //console.log(id_comment);

        if($(this).hasClass('btn-unlock')){// Bloquer commentaire
          $(this).removeClass('btn-unlock');
          $(this).addClass('btn-lock');
          moderationComment(id_membre, id_comment, 'non');
        } else if($(this).hasClass('btn-lock')){ // Débloquer commentaire
          $(this).removeClass('btn-lock');
          $(this).addClass('btn-unlock');
          moderationComment(id_membre, id_comment, 'oui');
        }
      });
    });

    // Page article : details + summary tags
    $('summary').on('click', function(){
      $(this).closest('details').toggleClass('txt-full');
    })

  });

})(jQuery);

