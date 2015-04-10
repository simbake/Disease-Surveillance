  <script>
  	
// select first list item
//$("li:first").addClass("active");

// select third list item
var liToSelect = 3;
$(".nav.nav-pills li:eq("+(liToSelect-1)+")").addClass("active");

// dynamically activate list items when clicked
$(".nav.nav-pills li").on("click",function(){
  $(".nav.nav-pills li").removeClass("active");
  $(this).addClass("active");
});

  </script>
  
<hr>
 
 <div class="row">
            <!-- <div class="col-lg-12">
                <h2 class="page-header">Menu Tabs</h2>
            </div> -->
            <div class="col-lg-12">

                <ul id="navFinished" class="nav nav-pills ">
                    <li class="active"><a href="<?php echo site_url("district_management");?>" data-toggle="tab"><i class="fa fa-tree"></i>Districts</a>
                    </li>
                    <li class="active"><a href="<?php echo site_url("county_management");?>" data-toggle="tab"><i class="fa fa-car"></i> Counties</a>
                    </li>
                    <li class="active"><a href="<?php echo site_url("facility_management");?>" data-toggle="tab"><i class="fa fa-support"></i>Facilities</a>
                    </li>
                    <li class="active"><a href="<?php echo site_url('disease_ranking');?>" data-toggle="tab"><i class="fa fa-database"></i>Disease Ranking</a>
                    </li>
                    <li class="active"><a href="<?php echo site_url("user_management/listing");?>" data-toggle="tab"><i class="fa fa-database"></i> Users</a>
                    </li>
                    <li class="active"><a href="<?php echo site_url("report_upload");?>" data-toggle="tab"><i class="fa fa-database"></i>Upload</a>
                    </li>
                </ul>

               

            </div>
        </div>
        
     <hr>
<!-- <div id="sub_menu">
<a href="<?php echo site_url("district_management");?>" class="top_menu_link sub_menu_link first_link <?php if($quick_link == "district_management"){echo "top_menu_active";}?>">Districts</a>
<a href="<?php echo site_url("county_management");?>" class="top_menu_link sub_menu_link  <?php if($quick_link == "county_management"){echo "top_menu_active";}?>">Counties</a>
<a href="<?php echo site_url("facility_management");?>" class="top_menu_link sub_menu_link <?php if($quick_link == "facility_management"){echo "top_menu_active";}?>">Facilities</a>
<a href="<?php echo site_url('disease_ranking');?>" class="top_menu_link sub_menu_link  <?php if ($quick_link == "disease_ranking") {echo "top_menu_active";}?>">Disease Ranking</a>
<a href="<?php echo site_url("user_management/listing");?>" class="top_menu_link sub_menu_link <?php if($quick_link == "user_management"){echo "top_menu_active";}?>">Users</a>
<a href="<?php echo site_url("report_upload");?>" class="top_menu_link last_link sub_menu_link <?php if($quick_link == "report_upload"){echo "top_menu_active";}?>">Upload</a>


</div> -->

<?php 
$this->load->view($module_view);
?>
