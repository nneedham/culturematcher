<div class="list-group-item">

  <?php
    $link = 'href="#company';
    $link = $link . $x;
    $link = $link . '"';

    $destination = 'id="company';
    $destination = $destination . $x;
    $destination = $destination . '"';

  ?>
  <!-- Toggle -->
  <div class ="row">
  <div class="col-11">
  <a class="d-flex align-items-center text-reset text-decoration-none" data-toggle="collapse" <?php echo $link; ?> role="button" aria-expanded="false" aria-controls="helpOne">

    <!-- Title -->
    <span class="mr-4" style="text-align:center;" style="vertical-align:center;">
        <?php print_r($match_score. "% "); ?>
        <p>Match Score</p>
    </span>
    <span class="mr-4" style="font-size:150%;" style="text-align:center;" style="vertical-align:center;">
      <?php print_r($row['company_name']); ?>
    </span>
    <!-- Metadata -->
    <div class="text-muted ml-auto">

      <!-- Text -->
      <span class="font-size-sm mr-4 d-none d-md-inline">
        Click for more details
      </span>





    </div>

  </a>
</div>
  <!-- bookmark -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <div class="bookmark" class="col-1 align-items-center">
    <div class="<?php echo $company_id ?>" id="<?php echo $company_id ?>">
      <img src="./assets/img/icons/duotone-icons/General/Bookmark.svg" width="200%" height="200%" id="<?php echo $company_id ?>" class="bookmark_on<?php echo $company_id ?>" <?php if (!$bookmark_bool) {echo 'style="display: none;"';} ?>>
      <img src="./assets/img/icons/duotone-icons/General/bookmark_empty.svg" width="200%" height="200%" class="bookmark_off<?php echo $company_id ?>" id="<?php echo $company_id ?>" <?php if ($bookmark_bool) {echo 'style="display: none;"';} ?>>
    </div>
  </div>
  <script>
    $(document).ready(function(){
        $('.<?php echo $company_id ?>', $('.bookmark')).click(function(){
            var id = $(this).attr('id');
            $('.bookmark_on<?php echo $company_id ?>').toggle();
            $('.bookmark_off<?php echo $company_id ?>').toggle();
            $.ajax({
              type: "POST",
              url: "./pages/bookmarks/button_test_function.php?q=" + id,
              data: { id: id }
            }).done(function( msg ) {

            });
        });
    });
  </script>
</div>

  <!-- Collapse -->
  <div class="collapse" <?php echo $destination; ?> data-parent="#helpAccordionOne">
    <div class="py-5">

      <!-- Text -->
      <p class="text-gray-700">
        <b>Employee count:</b> <?php echo round($row['employees'],1); ?>
      </p>
      <p class="text-gray-700">
        <b>Sector:</b> <?php echo $row['sector']; ?>
      </p>
      <p class="text-gray-700">
        <b>Industry:</b> <?php echo $row['industry']; ?>
      </p>
      <p class="text-gray-700">
        <b>HQ Location:</b> <?php echo $row['hq_state']; ?>
      </p>


    </div>
  </div>

</div>
