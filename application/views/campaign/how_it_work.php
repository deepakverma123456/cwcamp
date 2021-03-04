<!-- page content -->
<div class="right_col" role="main">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h2>URL Sanitization</h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
				<div class="container">
					<p>
						<b>URL Sanitization</b> is a process of cleaning a URL before adding it into system. This is recommended that one should add a properly sanitized url inside the system.
						<h5><strong>Why to do sanitization?</strong></h5>
						A web page url can be without parameter or with parameter, every parameter of a URL is separated by '&'. 
						It has been found that in most of the cases, not all parameters required to access a web page. Thus we have to find out
						the minimum parameter required to access a web page. 
						This also shorten the URL and keep it readable.	
						<br/>
						Sanitization required some extra effort, since one need to hit and trial the URL many time to obtain the shortest url.

						<h5><strong>How to do sanitization?</strong></h5>
						Suppose you have retailer page url like below:<br/>
						<u>http://t.yhd.com/detail/4230915_1?tc=3.0.5.50644893.1&tp=51.clear.124.1.1.LecpQiP-10-2R1QT&prid=1&abtest=1.378_629_2225&ti=7KMEOL</u>
						<br/>
						Since every url parameter is separated by '&' thus, start from right and try to remove one or more parameter, and then run the url in browser
						Ex: <u>http://t.yhd.com/detail/4230915_1?tc=3.0.5.50644893.1&tp=51.clear.124.1.1.LecpQiP-10-2R1QT&prid=1</u>
						<br/>
						Repeat the same until you get the shortest url, that can be used to access the same web page.
						In this case <u>http://t.yhd.com/detail/4230915</u> is the shortest url obtained.
						<h5><strong>How to test?</strong></h5>
						This is important and highly suggested to user, to test the obtained sanitized url. <br/>
						For this you need to open the sanitized url once in different browser, other than the browser form where it has been obtained.
						<em>The URL should open correctly in other browsers after being sanitized.</em>
					</p>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /page content -->
