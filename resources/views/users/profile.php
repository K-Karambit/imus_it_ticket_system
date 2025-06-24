<style>
  #profile {
    font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
  }
</style>




<div class="content-wrapper" style="overflow: scroll;">


  <!-- <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">User Profile</li>
          </ol>
        </div>
      </div>
    </div>
  </section> -->


  <section class="content">


    <?php include __DIR__ . '/../../components/overviewComponent.php';  ?>


    <div class="container-fluid">
      <div class="row">
        <div class="col-md-4">


          <?php include __DIR__ . '../../../components/ProfileComponent.php';  ?>


        </div>



        <div class="col-md-8">

          <?php include __DIR__ . '../../../components/TicketComponent.php';  ?>

        </div>
      </div>
    </div>
  </section>



































</div>