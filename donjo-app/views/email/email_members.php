<div class="static-content-wrapper">
<div class="static-content">
<div class="page-content">
<ol class="breadcrumb">
   <li class=""><a href="">Home</a></li>
   <li class=""><a href="">Email Members</a></li>
</ol>

<form action="<?php bs() ?>email/send_email" method="post" accept-charset="utf-8">

<div class="container-fluid">
   <div data-widget-group="group1">
      <div class="row">
         <div class="col-md-12">
            <div class="alert alert-success mb-xl">
               <h3><i class="fa fa-paper-plane" aria-hidden="true"></i> Email to Members</h3>
               <p>
                  You can use this part of the Admin Panel to send out emails to members of your site.Choose option to send Emails.
               </p>
               <div class="checkbox checkbox">
                  <input type="radio" name="check" id="radio7" value="members" checked>
                  <label for="radio7">
                  Send Email to Members
                  </label>
               </div>
               <div class="checkbox checkbox-danger">
                  <input type="radio" name="check" id="radio9" value="group">
                  <label for="radio9">
                  Send Email to Group
                  </label>
               </div>
            </div>
         </div>
      </div>
  
      <div class="row email_members">
         <div class="col-md-12">
            <div class="panel panel-success">
               <div class="panel-heading">
                  <h2>Users Records</h2>
                  <div class="panel-ctrls"></div>
               </div>
               <div class="panel-body no-padding">
                  <table id="example" class="table table-striped table-bordered" cellspacing="0" width="100%">
                     <thead>
                        <tr>
                           <th>First Name</th>
                           <th>Last Name</th>
                           <th>User Name</th>
                           <th>Email</th>
                           <th>
                               Select Users
                           </th>
                        </tr>
                     </thead>
                     <tbody>
                        <?php foreach ($users as $user):?>
                        <tr>
                           <td>
                              <?php echo htmlspecialchars($user->first_name,ENT_QUOTES,'UTF-8');?>
                           </td>
                           <td>
                              <?php echo htmlspecialchars($user->last_name,ENT_QUOTES,'UTF-8');?>
                           </td>
                           <td>
                              <?php echo htmlspecialchars($user->username,ENT_QUOTES,'UTF-8');?>
                           </td>
                           <td>
                              <?php echo htmlspecialchars($user->email,ENT_QUOTES,'UTF-8');?>
                           </td>
                           <td>
                              <div class="checkbox checkbox-info checkbox-circle">
                                  <input id="checkbox8" class="styled" name="foo[]" value="<?php echo $user->email ?>" type="checkbox">
                                  <label for="checkbox8">
                                  </label>
                              </div>
                           </td>
                        </tr>
                        <?php endforeach;?>
                     </tbody>
                  </table>
               </div>
               <div class="panel-footer"></div>
            </div>
         </div>
      </div>
      <div class="row email_group" style="display: none;">
         <div class="col-md-12">
            <div class="panel panel-success">
               <div class="panel-heading"><i class="fa fa-users" aria-hidden="true"></i> Select Group</div>
               <div class="panel-body">
                 <div class="form-group col-md-5">
                    <label for="exampleInputEmail1">Select user Group</label>
                    <select name="group_name" class="form-control">
                      <?php 
                            $groups = $this->ion_auth->groups()->result(); 
                      ?>

                      <?php foreach ($groups as $key => $value): ?>
                        <option value="<?php echo $value->id ?>"><?php echo $value->name; ?></option>
                      <?php endforeach ?>

                    </select>
                 </div>
               </div>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-md-12">
            <div class="panel panel-success">
               <div class="panel-body">
                   <div class="form-group">
                      <label for="exampleInputEmail1">Title</label>
                      <input type="text" class="form-control" name="title" required>
                   </div>
                   <div class="form-group">
                      <label for="exampleInputEmail1">Message</label>
                      <textarea name="msg" id="summernote" class="form-control" rows="5"></textarea>
                   </div>
                   <!-- <div class="form-group"> -->
                     <button type="submit" class="btn btn-success">
                      <i class="fa fa-paper-plane" aria-hidden="true"></i> Send</button>
                   <!-- </div> -->
               </div>
            </div>
         </div>
      </div>
  </form>
      <!-- .container-fluid -->
   </div>
   <!-- #page-content -->
</div>
<script>
   $('body').on('change', '#radio9', function(event) {
     event.preventDefault();
     /* Act on the event */
     $('.email_members').hide('1000');
     $('.email_group').show('1000');
   });
   
   $('body').on('change', '#radio7', function(event) {
     event.preventDefault();
     /* Act on the event */
     $('.email_members').show('1000');
     $('.email_group').hide('1000');
   }); 


$(document).ready(function() {
  $('#summernote').summernote();
}); 
</script>
