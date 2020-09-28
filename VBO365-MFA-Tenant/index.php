<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css" />
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/clipboard.min.js"></script>
<title>VBO365 Tenant MFA Authentication Example</title>
<style>
.wizard .nav-tabs {
	position: relative;
	margin-bottom: 0;
	border-bottom-color: transparent;
}
.wizard > div.wizard-inner {
	position: relative;
}
.connecting-line {
    height: 2px;
    background: #e0e0e0;
    position: absolute;
    width: 100%;
    margin: 0 auto;
    top: 50%;
    z-index: 1;
}
.wizard .nav-tabs > li.active > a, .wizard .nav-tabs > li.active > a:hover, .wizard .nav-tabs > li.active > a:focus {
    color: #555555;
    cursor: default;
    border: 0;
    border-bottom-color: transparent;
}
span.round-tab {
    width: 30px;
    height: 30px;
    line-height: 30px;
    display: inline-block;
    border-radius: 50%;
    background: #fff;
    z-index: 2;
    position: absolute;
    left: 0;
    text-align: center;
    font-size: 16px;
    color: #0e214b;
    font-weight: 500;
    border: 1px solid #ddd;
}
span.round-tab i{
    color:#555555;
}
.wizard li.active span.round-tab {
        background: #0db02b;
    color: #fff;
    border-color: #0db02b;
}
.wizard li.active span.round-tab i{
    color: #5bc0de;
}
.wizard .nav-tabs > li.active > a i{
	color: #0db02b;
}
.wizard .nav-tabs > li {
    width: 25%;
}
.wizard li:after {
    content: " ";
    position: absolute;
    left: 46%;
    opacity: 0;
    margin: 0 auto;
    bottom: 0px;
    border: 5px solid transparent;
    border-bottom-color: red;
    transition: 0.1s ease-in-out;
}
.wizard .nav-tabs > li a {
    width: 30px;
    height: 30px;
    margin: 20px auto;
    border-radius: 100%;
    padding: 0;
    background-color: transparent;
    position: relative;
    top: 0;
}
.wizard .nav-tabs > li a i{
	position: absolute;
    top: -15px;
    font-style: normal;
    font-weight: 400;
    white-space: nowrap;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 12px;
    font-weight: 700;
    color: #000;
}
.wizard .nav-tabs > li a:hover {
    background: transparent;
}
.wizard .tab-pane {
    position: relative;
    padding-top: 20px;
}
.prev-step,
.next-step{
    font-size: 13px;
    padding: 8px 24px;
    border: none;
    border-radius: 4px;
    margin-top: 30px;
}
.next-step{
	background-color: #0db02b;
}
</style>
<script>
$(document).ready(function() {
	function storeValue(key, value) {
		if (localStorage) {
			localStorage.setItem(key, value);
		} else {
			$.cookies.set(key, value);
		}
	}

	function getStoredValue(key) {
		if (localStorage) {
			return localStorage.getItem(key);
		} else {
			return $.cookies.get(key);
		}
	}

    $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
        var target = $(e.target);
    
        if (target.parent().hasClass('disabled')) {
            return false;
        }
    });

    $('.next-step').click(function(e) {
        var active = $('.wizard .nav-tabs li.active');
		var step = $(this).parents('.tab-pane').attr('id');
		var clientid = $('#clientid').val();
		var tenantid = $('#tenantid').val();
		var token;
		
		if (step == 'step1') {	
			$.post('action.php', {'action' : 'getdevicecode', 'clientid' : clientid, 'tenantid' : tenantid}).done(function(data) {
				if (data.match(/error/i)) {
					console.log('error');
					return;
				}
				
				var response = JSON.parse(data);
				var code = response['user_code'];
				var devicecode = response['device_code'];

				storeValue('devicecode', devicecode);
				
				$('#user-code').val(code);
			});
		} else if (step == 'step2') {
			var devicecode = getStoredValue('devicecode');
			
			$.post('action.php', {'action' : 'gettoken', 'clientid' : clientid, 'tenantid' : tenantid, 'devicecode' : devicecode}).done(function(data) {
				storeValue('assertion', data);
				$('#graphlogin').val(data);
			});
		} else if (step == 'step3') {
			var host = $('#vboserver').val();	
			var assertion = getStoredValue('assertion');

			$.post('action.php', {'action' : 'login', 'host' : host, 'tenantid' : tenantid, 'assertion' : assertion}).done(function(data) {
				$('#vbologin').val(data);
			});
		}
		
		active.next().removeClass('disabled');
        nextTab(active);
    });
	
    $('.prev-step').click(function(e) {
        var active = $('.wizard .nav-tabs li.active');
		
        prevTab(active);
    });
});

function nextTab(elem) {
    $(elem).next().find('a[data-toggle="tab"]').click();
}
function prevTab(elem) {
    $(elem).prev().find('a[data-toggle="tab"]').click();
}
</script>
<br /><br />
<div class="container">
	<div class="col-md-10">
		<div class="wizard">
			<div class="wizard-inner">
				<div class="connecting-line"></div>
				<ul class="nav nav-tabs" role="tablist">
					<li role="presentation" class="active">
						<a href="#step1" data-toggle="tab" aria-controls="step1" role="tab" aria-expanded="true"><span class="round-tab">1 </span> <i>Step 1</i></a>
					</li>
					<li role="presentation" class="disabled">
						<a href="#step2" data-toggle="tab" aria-controls="step2" role="tab" aria-expanded="false"><span class="round-tab">2</span> <i>Step 2</i></a>
					</li>
					<li role="presentation" class="disabled">
						<a href="#step3" data-toggle="tab" aria-controls="step3" role="tab" aria-expanded="false"><span class="round-tab">3</span> <i>Step 3</i></a>
					</li>
					<li role="presentation" class="disabled">
						<a href="#step4" data-toggle="tab" aria-controls="step4" role="tab" aria-expanded="false"><span class="round-tab">4</span> <i>Step 4</i></a>
					</li>
				</ul>
			</div>		
			<div class="tab-content">
				<div class="tab-pane active fade in" role="tabpanel" id="step1">
					<h4 class="text-center">Step 1: Login against Microsoft Graph API</h4>
					<div class="row">
						<div class="form-group col-md-9">
							<label>Tenant ID:</label> 
							<input class="form-control" type="text" id="tenantid" value="esdeka.onmicrosoft.com"> 
							<label>Application ID (client ID):</label> 
							<input class="form-control" type="text" id="clientid" value="c4b96bb7-bf65-42ad-a189-5b1a4493608f"> 
						</div>
					</div>
					<ul class="list-inline">
						<li><button type="button" class="default-btn next-step">Next</button></li>
					</ul>
				</div>
				<div class="tab-pane fade in" role="tabpanel" id="step2">
					<h4 class="text-center">Step 2: Device Code</h4>
					<input type="text" readonly id="user-code">
					<button class="btn" data-clipboard-target="#user-code">Copy to clipboard</button><br /><br />
					To sign in, use a web browser to open the page <a href="https://microsoft.com/devicelogin" target="_blank">https://microsoft.com/devicelogin</a> and enter the below code to authenticate.<br><b>Hitting next without doing this will result in an error!</b>
					<script>
					$('.btn').tooltip({
					  trigger: 'click',
					  placement: 'bottom'
					});

					function setTooltip(message) {
					  $('.btn').tooltip('hide')
						.attr('data-original-title', message)
						.tooltip('show');
					}

					function hideTooltip() {
					  setTimeout(function() {
						$('.btn').tooltip('hide');
					  }, 1000);
					}

					var clipboard = new ClipboardJS('.btn');

					clipboard.on('success', function(e) {
					  setTooltip('Copied!');
					  hideTooltip();
					});

					clipboard.on('error', function(e) {
					  setTooltip('Failed!');
					  hideTooltip();
					});
					</script>
					<ul class="list-inline">
						<li><button type="button" class="default-btn prev-step">Back</button></li>
						<li><button type="button" class="default-btn next-step">Next</button></li>
					</ul>
				</div>
				<div class="tab-pane fade in" role="tabpanel" id="step3">
					<h4 class="text-center">Step 3: Authentication against Microsoft Graph API</h4>						
					<div class="row">
						<div class="form-group col-md-9">
							<label>VBO server hostname:</label> 
							<input class="form-control" type="text" id="vboserver" value="127.0.0.1"><br />
							<label>Microsoft Graph API result (assertion):</label>
							<textarea id="graphlogin" rows="20" cols="145" readonly></textarea>
						</div>
					</div>
					<ul class="list-inline">
						<li><button type="button" class="default-btn prev-step">Back</button></li>
						<li><button type="button" class="default-btn next-step">Next</button></li>
					</ul>
				</div>
				<div class="tab-pane fade in" role="tabpanel" id="step4">
					<h4 class="text-center">Step 4: Authentication against VBO365</h4>						
					<div class="row">
						<div class="form-group col-md-9">
							<label>VBO server authentication result:</label>
							<textarea id="vbologin" rows="20" cols="145" readonly></textarea>
						</div>
					</div>
					<ul class="list-inline">
						<li><button type="button" class="default-btn prev-step">Back</button></li>
					</ul>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
</div>