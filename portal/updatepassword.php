<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Update Password | Lumi World</title>
		
		<?php include('includes/head.php')?>
		<script type="text/javascript">
      var currentValid = true;
      var oEnterprise = null;
      $(document).ready(function () {
          init();
          setupLoadingScreen();
          //$('#txt-current').change(validateCurrentPassword);
          $('#btn-change').click(changePassword);
          $('#btn-cancel').click(function () {
              window.location.replace('search.php')
          });
          oEnterprise = JSON.parse(sessionStorage.getItem('enterprise'));
          $('#txt-email').val(oEnterprise.username);

      });
      

      function isValid(input) {
          var reg = /^[^%\s]{6,}$/;
          var reg2 = /[a-zA-Z]/;
          var reg3 = /[A-Z]/;
          var reg4 = /[0-9]/;
          return reg.test(input) && reg2.test(input) && reg3.test(input) && reg4.test(input);
      }
      function changePassword() {
          if ($('#txt-new').val() !== $('#txt-confirm').val()) {
              bootbox.alert("new password does not match confirm password", function () {

              });
          }
          else if (!isValid($('#txt-new').val())) {
              bootbox.alert("Weak password, password must be at least 8 characters, contain 1 upper case, 1 lower case and, 1 number.", function () {

              });
          }
          else if (!currentValid) {
              bootbox.alert("current password is incorrect", function () {
                  $('#txt-current').val('');
              });
          }
          else {

              var dataEnterpriseUpdate = JSON.stringify({
                  'password': $('#txt-new').val(),
                  'username': oEnterprise.username,
                  'enterprisename': oEnterprise.name,
                  'key': 'updateenterpriseauth'
              });

              $.ajax({
                  type: "POST",
                  url: "http://" + serverURL + ":12020/enterpriseprofile",
                  data: {
                      params: dataEnterpriseUpdate
                  },
                  contentType: "application/x-www-form-urlencoded",
                  dataType: "json",
                  success: function (data) {
                      //console.log(data);
                      bootbox.alert("Your password has been successfully updated", function () {
                          window.location.replace("search.php");
                      });
                  }
              });
          }
      }
		</script>
	</head>

	<body>
		<?php include('includes/topmenu.php')?>
            <section class="content" id="searchtab">
				<?php include('includes/tabs.php')?>
                <?php include('includes/leftmargin.php')?>
                    
                <div class="main-content">
				    <div class="inner-content">
					    <div class="content-header"><span>User Administration</span>
                            <input id="btn-change" type="button" value="save" class="login-button fr" />
                            <input id="btn-cancel" type="button" value="cancel" class="login-button fr" />
                        </div>
                        <h4>Password Management</h4>
				        <form>
					        <div class="form-item">
                                <span class="form-label">Email address</span>
						        <input class="privatetextstyle" type="text" id="txt-email" name="name" placeholder="Current Password" />
					        </div>
					        <div class="form-item">
                                <span class="form-label">Password</span>
						        <input class="privatetextstyle" type="password" id="txt-new" name="name" placeholder="New Password" />
					        </div>
					        <div class="form-item">
                                <span class="form-label">Confirm Password</span>
						        <input class="privatetextstyle" type="password" id="txt-confirm" name="name" placeholder="Confirm Password" />
					        </div>
				        </form>
			        </div>
			
		        </div>
		</section>
		

		<script>
			window.jQuery || document.write('<script src="js/vendor/jquery-1.10.1.min.js"><\/script>')
		</script>
		

		<script src="js/plugins.js"></script>
		<script src="js/main.js"></script>
		<script src="js/bootbox.js"></script>
		<script src="js/bootstrap.js"></script>
        <div class="modal-loading"><!-- Place at bottom of page --></div>
	</body>
</html>
