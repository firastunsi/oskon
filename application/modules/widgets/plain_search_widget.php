<form action="<?php echo site_url('show/advfilter');?>" method="post">
              <div class="search-bar">

                 <h2 class="detail-title"><i class="fa fa-search"></i>&nbsp;<?php echo lang_key('DBC_ADVANCED_SEARCH'); ?></h2>

              </div>

              <div  class="orange-border panel panel-primary effect-helix in" id="plain_box">

                  <div class="panel-heading orange"><?php echo lang_key('plain_search'); ?><a class="up" style="float:right" data-action="collapse" href="#plain_box"><i class="fa fa-chevron-up"></i></a></div>

                  <div class="panel-body">

                      <?php $chk = (isset($data['ignor_plain']))?'checked="checked"':'';?>

                      <input <?php echo $chk;?> target="#plain_container" type="checkbox" name="ignor_plain" id="ignor_plain" value="yes">

                      <label for="ignor_plain"><?php echo lang_key('ignore_this_section'); ?></label>

                      <span id="plain_container">

                        <div class="info_list">                      

                              <input class="form-control" type="text" value="<?php echo (isset($data['plainkey']))?rawurldecode($data['plainkey']):'';?>" name="plainkey">

                          </div>

                        <button type="submit" class="btn btn-info  btn-labeled" style="margin:10px 0 10px 0">

                        Search

                        <span class="btn-label btn-label-right">

                           <i class="fa fa-search"></i>

                        </span>

                        </button>

                      </span>  

                  </div>

             </div>  
</form>