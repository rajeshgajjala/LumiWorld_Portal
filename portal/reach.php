<!DOCTYPE html>
<html lang="en">
	<head>
        <title>Reach | Lumi World</title>
        <?php include('includes/head.php')?>
        <script src="js/view.js?v=0.1"></script>
        <script src="js/reach.js?v=0.2"></script>
        <script src="js/tinymce/tinymce.min.js"></script>
        <script>
            
        </script>
	</head>
	<body>
        
		<?php include('includes/topmenu.php')?>
		
        <!-- Content Wrapper -->
        <section class="content" id="searchtab">
            <?php include('includes/leftmargin.php')?>
					
			<div class="main-content">
				<div id="reach-history" class="inner-content">
					<div class="content-header"><span>History</span><input id="new-message" type="button" value="+New Message" class="login-button fr" /></div>
                    <div class="clearfix">
                        <table class="history-table" style="width: 100%">
                            <thead>
                                <tr><th>Type</th><th style="width: 30%">Message Body</th><th style="width: 30%">Recipients</th><th>Sent</th><th>Delivery Attempt Expires On</th></tr>
                            </thead>
                            <tbody id="history-body">
                                
                            </tbody>
                        </table>
                        <div class="pagination" style="width: 440px; display: inline-block">
                            <div style="margin-top: 5px" class="fl">
                                <select style="width: 80px;" id="sel-page-limit">
                                    <option selected="selected">10</option>
                                    <option>20</option>
                                    <option>50</option>
                                </select>
                                RECORDS PER PAGE
                            </div>
                            <div class="grid-pager fr" id="gird-pager">
                            </div>
                        </div>
                    </div>
				</div>
				<div id="reach-compose-recipients" class="inner-content">
					<div class="content-header"><span>Reach Message Composer</span><input id="btn-next-recipients" type="button" value="Next" class="login-button fr" /></div>
                    <div class="message-steps">
                        <div class="message-step-active fl" id="step-recipients" style="z-index: 3">
                            1. Recipients
                        </div>
                        <div class="message-step fl" id="step-message" style="margin-left: -20px; z-index: 2">
                            2. Message
                        </div>
                        <div class="message-step fl" id="step-review" style="margin-left: -20px; z-index: 1">
                            3. Review & Send
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-item">
                        <span class="form-label" style="margin-right: 20px; font-weight: 700">Message Recipient</span>
                        <label style="margin-right: 10px;"><input style="vertical-align: middle; margin: 0px;" checked="checked" id="rad-individual" type="radio" title="Individual" name="message-recipient" value="1" />Individuals</label> 
                        <label><input style="vertical-align: middle; margin: 0px;" id="rad-groups" type="radio" name="message-recipient" value="2" />Groups</label>
                    </div>
                    
                    <div class="clearfix"></div>
                    
                    <div id="groups-container" style="display: none;">
						<div class="form-item">
                            <span class="form-label" style="margin-right: 20px; font-weight: 700">Select Group</span>
                            <select id="combo-groups" style="width: 60%">

                            </select>
                                <!--<input class="login-button" id="search-customers" value="Search Luminet World" type="button" />-->
                        </div>
					</div>
                    <div id="individuals-container">
                        <div class="form-item">
                            <span class="form-label" style="margin-right: 20px; font-weight: 700">Search Individuals</span>
                            <form onsubmit="searchCustomer(); return false;">
                                <input class="txt-search fl" id="txt-search" type="text"><br>
                                <!--<input class="login-button" id="search-customers" value="Search Luminet World" type="button" />-->
                            </form>
                        </div>
                        <div class="form-item" id="live-search" style="display: none">
                            <span class="form-label" style="margin-right: 20px; font-weight: 700">Results</span>
                            <div class="search-results">
                                <ul>
                                </ul>
                            </div>
                        </div>
                        <div id="individual-search-results" style="margin-top: 20px; display: none">
                            <div id="total-results-container">
                                <span class="form-label">Search Result:</span>
                                <span id="total-results" class="total-results" style="margin-left: 10px;">No existing user found</span>
                            </div>
                            
                            <div id="individual-results-table">
                                <table border="1" id="search-results" style="border: 1px solid red;">
                                    <tr><td style="width: 30%">Mobile No.</td><td style="width: 30%">Status</td><td style="width: 40%"></td></tr>
                                    <tr><td id="cell-number"></td><td>Connected</td><td><input id="btn-add-recipient" type="button" class="login-button" value="Add Recipient" /></td></tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-item">
                        <span id="lbl-recipients" class="form-label" style="margin-right: 20px; font-weight: 700">Recipients List</span>
                        <div class="selected-numbers" id="selected-numbers">
                        </div>
                    </div>
				</div>

                <div id="reach-compose-schedule" class="inner-content">
					<div class="content-header"><span>Reach Message Composer</span><input id="btn-next-schedule" type="button" value="Next" class="login-button fr" /><input id="btn-back-schedule" type="button" value="Back" class="login-button fr" /></div>
                    <div class="message-steps">
                        <div class="message-step fl" id="step-recipients" style="z-index: 3">
                            1. Recipients
                        </div>
                        <div class="message-step-active fl" id="step-message" style="margin-left: -20px; z-index: 2">
                            2. Message
                        </div>
                        <div class="message-step fl" id="step-review" style="margin-left: -20px; z-index: 1">
                            3. Review & Send
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-item">
                        <span class="form-label" style="margin-right: 20px; font-weight: 700">Message Validity:</span>
                        <label style="margin-right: 10px;"><input id="rad-no-expiry" style="vertical-align: middle; margin: 0px;" checked="checked" type="radio" name="schedule-type" value="1" />No Expiry</label> 
                        <label><input id="rad-limited" style="vertical-align: middle; margin: 0px;" type="radio" name="schedule-type" value="2" />Time Limited</label>
                    </div>
                    <div id="cont-message-lifetime" style="display: none" class="form-item">
                        <span class="form-label" style="height: 30px; line-height: 30px; margin-right: 20px; width: 200px">Attempt to deliver message up to:</span>
                        <select id="message-life" style="width: 80px">
                        </select>
                        <select id="message-life-unit" style="width: 100px">
                            <option>days</option>
                            <option>hours</option>
                            <option>weeks</option>
                        </select>
                        <span>after sending</span>
                    </div>
				</div>

				<div id="reach-compose-message" class="inner-content">
					<div class="content-header"><span>Reach Message Composer</span><input id="btn-next-compose" type="button" value="Next" class="login-button fr" /><input id="btn-back-compose" type="button" value="Back" class="login-button fr" /></div>
                    <div class="message-steps">
                        <div class="message-step fl" id="step-recipients" style="z-index: 3">
                            1. Recipients
                        </div>
                        <div class="message-step-active fl" id="step-message" style="margin-left: -20px; z-index: 2">
                            2. Message
                        </div>
                        <div class="message-step fl" id="step-review" style="margin-left: -20px; z-index: 1">
                            3. Review & Send
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-item">
                        <span class="form-label" style="margin-right: 20px; font-weight: 700">Message format</span>
                        <label style="margin-right: 10px;"><input id="rad-plain" style="vertical-align: middle; margin: 0px;" checked="checked" type="radio" name="message-type" value="1" />Plain Text</label> 
                        <label><input id="rad-rte" style="vertical-align: middle; margin: 0px;" type="radio" name="message-type" value="2" />Rich Media</label>
                    </div>
                    <div class="form-item">
                        <span class="form-label">Message</span>
                        <textarea id="txt-message" style="width: 400px; height: 120px" ></textarea>
                        <div id="div-rte" style="margin-left: 170px; display: none">
                            <textarea id="txt-rte" style="width: 320px; height: 460px" ></textarea>
                        </div>
                        <div id="chars-left" style="text-align: right; width: 580px;padding-right: 10px">500 characters left</div>
                    </div>
                    
				</div>
				<div id="reach-compose-send" class="inner-content">
					<div class="content-header"><span>Reach Message Composer</span><input id="btn-send" type="button" value="Send" class="login-button fr" /><input id="btn-back-send" type="button" value="Back" class="fr login-button" /></div>
                    <div class="message-steps">
                        <div class="message-step fl" id="step-recipients" style="z-index: 3">
                            1. Recipients
                        </div>
                        <div class="message-step fl" id="step-message" style="margin-left: -20px; z-index: 2">
                            2. Message
                        </div>
                        <div class="message-step-active fl" id="step-review" style="margin-left: -20px; z-index: 1">
                            3. Review & Send
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="form-item">
                        <span class="form-label">Recipients</span>
                        <span style="width: 150px;" id="view-recipient-type" class="fl"></span>
                        <span id="view-all-recipients" style="display: table-cell"></span>
                    </div>
                    <div class="form-item" style="margin-bottom: 0;">
                        <span class="form-label">Message</span>
                        <span style="width: 150px;" class="fl">Type</span>
                        <span id="view-message-type">Plain Text</span>
                    </div>
                    <div class="form-item">
                        <span class="form-label">&nbsp;</span>
                        <span style="width: 150px;" class="fl">Body</span>
                        <span style="display: table-cell" id="view-message-body">&nbsp;</span>
                    </div>

                    <div class="form-item" style="margin-bottom: 0;">
                        <span class="form-label">Message Preview:</span>
                        <span style="width: 150px;" id="view-recipient-type" class="fl"></span>
                        <span id="view-message-type"><a href="javascript:overlay();"><img alt="preview android" src="img/70_android icon preview.png"></a></span>
                    </div>

                    <div class="form-item" style="margin-bottom: 0;">
                        <span class="form-label">Scheduling:</span>
                        <span style="width: 150px;" class="fl">Scheduling</span>
                        <span id="view-scheduling"></span>
                    </div>

                    <div class="form-item">
                        <span class="form-label">Estimated Recipients</span>
                        <span id="recipients-count"style="width: 150px;" class="fl">&nbsp;</span>
                    </div>

				</div>
            </div>
        </section>
        

		
        <div id="overlay">
             <div id="phone-priview">
                  <div id="phone-priview-screen">
                      <div class="phone-top-bar"></div>
                      <div class="phone-header">
                          <div class="phone-header-logo">
                              <img id="phone-header-logo" alt="logo" src="img/logo.png" />
                          </div>
                          <div>
                              <span class="phone-header-name"></span><br>
                              <span class="phone-header-timestamp"></span>
                          </div>
                      </div>
                      <div class="phone-header-tools">
                          <div class="phone-call"></div>
                          <div class="phone-disconnect"></div>
                      </div>
                      <div class="phone-message">
                      </div>
                  </div>
             </div>
        </div>

        <div class="modal-loading"><!-- Place at bottom of page --></div>
	</body>
</html>
