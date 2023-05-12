<!--MAIN NAVIGATION-->
			<!--===================================================-->
			<nav id="mainnav-container">
				<div id="mainnav">

				


					<!--Menu-->
					<!--================================-->
					<div id="mainnav-menu-wrap">
						<div class="nano">
							<div class="nano-content">
								<ul id="mainnav-menu" class="list-group">
						
									<!--Category name-->
									<!--<li class="list-header">Navigation</li>-->
						
									<!--Dashboard-->
									<li <?php if ($this->uri->segment(3) === 'dashboard') { echo 'class="active-link"'; } ?>>
										<a href="<?php echo admin_url() . 'admin/dashboard'; ?>">
											<i class="fa fa-dashboard"></i>
											<span class="menu-title">
												<strong>Dashboard</strong>
											</span>
										</a>
									</li>
									
								

									<!--User Management-->
									<li <?php if ($this->uri->segment(2) === 'users') { echo 'class="active-link"'; } ?>>
										<a href="<?php echo admin_url() . 'users'; ?>">
											<i class="fa fa-users"></i>
											<span class="menu-title">
												<strong>Users Management</strong>
											</span>
										</a>
										
									</li>
									
									<!--Sub Admins Management-->
									<!--<?php if ($this->ion_auth->is_super_admin()) { ?>
									<li <?php if ($this->uri->segment(2) === 'admins') { echo 'class="active-link"'; } ?>>
										<a href="<?php echo admin_url() . 'admins'; ?>">
											<i class="fa fa-users"></i>
											<span class="menu-title">
												<strong>Sub Admin Management</strong>
											</span>
										</a>
										<ul class="collapse <?php if ($this->uri->segment(2) === 'admins') { echo 'in'; } ?>">
											<li <?php if ($this->uri->segment(2) === 'admins' && $this->uri->segment(3) == '') { echo 'class="active-link"'; } ?>><a href="<?php echo admin_url() . 'admins'; ?>">View Sub Admin</a></li>
											<li <?php if ($this->uri->segment(2) === 'admins' && $this->uri->segment(3) === 'account_add') { echo 'class="active-link"'; } ?>><a href="<?php echo admin_url() . 'admins/account_add'; ?>">Add New Sub Admin</a></li> 
											</ul>
									</li>
									<?php } ?> -->

									

									



									<!--Wallet  Management-->
								<li <?php if ($this->uri->segment(2) == 'wallet' ) { echo 'class="active-link"'; } ?>>
										<a href="<?php echo admin_url() . 'wallet'; ?>">
											<i class="fa fa-ticket"></i>
											<span class="menu-title">
												<strong>Wallet Management</strong>
											</span>
										</a>
									</li> 
									
									<!--Currency pairs Management-->
									<li <?php if ($this->uri->segment(2) === 'pair' || $this->uri->segment(2) === 'trade_pairs') { echo 'class="active-link"'; } ?>>
										<a href="<?php echo admin_url() . 'users'; ?>">
											<i class="fa fa-money"></i>
											<span class="menu-title">
												<strong>Currency Pairs</strong>
											</span>
										</a>
										<!--Submenu-->
										<ul class="collapse <?php if ($this->uri->segment(2) == 'pair' || $this->uri->segment(2) == 'trade_pairs') { echo 'in'; } ?>">
											<li <?php if ($this->uri->segment(2) === 'pair') { echo 'class="active-link"'; } ?>><a href="<?php echo admin_url() . 'pair'; ?>">Exchanges</a></li>
											<li <?php if ($this->uri->segment(2) === 'trade_pairs') { echo 'class="active-link"'; } ?>><a href="<?php echo admin_url() . 'trade_pairs'; ?>">Tradings</a></li>
											</ul>
									</li>
									
								     <!--Orders Management-->
									<li <?php if ($this->uri->segment(2) == 'sell' || $this->uri->segment(2) == 'buy' || $this->uri->segment(2) == 'exchanges' || $this->uri->segment(2) == 'transactions') { echo 'class="active-link"'; } ?>>
										<a href="<?php echo admin_url() . 'users'; ?>">
											<i class="fa fa-list"></i>
											<span class="menu-title">
												<strong>Orders Management</strong>
											</span>
										</a>
										<!--Submenu-->
										<ul class="collapse <?php if ($this->uri->segment(2) == 'sell' || $this->uri->segment(2) == 'buy' || $this->uri->segment(2) == 'exchanges' || $this->uri->segment(2) == 'transactions') { echo 'in'; } ?>">
											<li <?php if ($this->uri->segment(2) === 'sell') { echo 'class="active-link"'; } ?>><a href="<?php echo admin_url() . 'sell'; ?>">Sell Orders</a></li>
											<li <?php if ($this->uri->segment(2) === 'buy') { echo 'class="active-link"'; } ?>><a href="<?php echo admin_url() . 'buy'; ?>">Buy Orders</a></li>
											<li <?php if ($this->uri->segment(2) === 'exchanges') { echo 'class="active-link"'; } ?>><a href="<?php echo admin_url() . 'exchanges'; ?>">Exchange Orders</a></li>
											<li <?php if ($this->uri->segment(2) === 'transactions') { echo 'class="active-link"'; } ?>><a href="<?php echo admin_url() . 'transactions'; ?>">Transactions</a></li>
											</ul>
									</li>
									
									
									
									<!--Payment gateways Management-->
									<li <?php if ($this->uri->segment(2) == 'gateways' ) { echo 'class="active-link"'; } ?>>
										<a href="<?php echo admin_url() . 'gateways'; ?>">
											<i class="fa fa-ticket"></i>
											<span class="menu-title">
												<strong>Payment Currencies</strong>
											</span>
										</a>
									</li>
									
									
										<!--Discounts  Management-->
									<!-- <li <?php if ($this->uri->segment(2) === 'discounts') { echo 'class="active-link"'; } ?>>
										<a href="<?php echo admin_url() . 'discounts'; ?>">
											<i class="fa fa-money"></i>
											<span class="menu-title">
												<strong>Discounts Management</strong>
											</span>
										</a>
										<ul class="collapse <?php if ($this->uri->segment(2) === 'discounts') { echo 'in'; } ?>">
											<li <?php if ($this->uri->segment(2) === 'discounts' && $this->uri->segment(3) !== 'add') { echo 'class="active-link"'; } ?>><a href="<?php echo admin_url() . 'discounts'; ?>">View Discounts</a></li>
											<li <?php if ($this->uri->segment(2) === 'discounts' && $this->uri->segment(3) === 'add') { echo 'class="active-link"'; } ?>><a href="<?php echo admin_url() . 'discounts/add'; ?>">Add Discount</a></li>
										</ul>
									</li> -->
									
									<!--Commisions  Settings-->
									<li <?php if ($this->uri->segment(2) === 'commisions') { echo 'class="active-link"'; } ?>>
										<a href="<?php echo admin_url() . 'commisions/edit/withdraw'; ?>">
											<i class="fa fa-money"></i>
											<span class="menu-title">
												<strong>Commissions Settings</strong>
											</span>
										</a>
										<!--Submenu-->
										<ul class="collapse <?php if ($this->uri->segment(2) === 'commisions') { echo 'in'; } ?>">
											<li <?php if ($this->uri->segment(2) === 'commisions' && $this->uri->segment(4) == 'withdraw') { echo 'class="active-link"'; } ?>><a href="<?php echo admin_url() . 'commisions/edit/withdraw'; ?>">Withdraw</a></li>
											<li <?php if ($this->uri->segment(2) === 'commisions' && $this->uri->segment(4) === 'trading') { echo 'class="active-link"'; } ?>><a href="<?php echo admin_url() . 'commisions/edit/trading'; ?>">Trading</a></li>
										</ul>
									</li>
									
									

									
									<!--CMS Management-->
									<li <?php if ($this->uri->segment(2) === 'cms') { echo 'class="active-link"'; } ?>>
										<a href="<?php echo admin_url() . 'cms'; ?>">
											<i class="fa fa-columns"></i>
											<span class="menu-title">
												<strong>CMS Management</strong>
											</span>
										</a>									
									</li>
									
									<!--Email template Management-->
									<li <?php if ($this->uri->segment(2) === 'email_template') { echo 'class="active-link"'; } ?>>
										<a href="<?php echo admin_url() . 'email_template'; ?>">
											<i class="fa fa-envelope-o"></i>
											<span class="menu-title">
												<strong>Email Templates</strong>
											</span>
										</a>										
									</li>
									
									<!--Meta Content Management-->
									<li <?php if ($this->uri->segment(2) === 'metacontent') { echo 'class="active-link"'; } ?>>
										<a href="<?php echo admin_url() . 'metacontent'; ?>">
											<i class="fa fa-outdent"></i>
											<span class="menu-title">
												<strong>Meta content</strong>
											</span>
										</a>										
									</li>
									
									
									
									<!--FAQ  Management-->
									<li <?php if ($this->uri->segment(2) === 'faq') { echo 'class="active-link"'; } ?>>
										<a href="<?php echo admin_url() . 'faq'; ?>">
											<i class="fa fa-mars"></i>
											<span class="menu-title">
												<strong>FAQ Management</strong>
											</span>
										</a>
										<!--Submenu-->
										<ul class="collapse <?php if ($this->uri->segment(2) === 'faq') { echo 'in'; } ?>">
											<li <?php if ($this->uri->segment(2) === 'faq' && $this->uri->segment(3) !== 'add') { echo 'class="active-link"'; } ?>><a href="<?php echo admin_url() . 'faq'; ?>">View FAQ</a></li>
											<li <?php if ($this->uri->segment(2) === 'faq' && $this->uri->segment(3) === 'add') { echo 'class="active-link"'; } ?>><a href="<?php echo admin_url() . 'faq/add'; ?>">Add FAQ</a></li>
										</ul>
									</li>
									
									
									<!--Our Team  Management-->
									<li <?php if ($this->uri->segment(2) === 'our_team') { echo 'class="active-link"'; } ?>>
										<a href="<?php echo admin_url() . 'our_team'; ?>">
											<i class="fa fa-mars"></i>
											<span class="menu-title">
												<strong>Our Team Management</strong>
											</span>
										</a>
										<!--Submenu-->
										<ul class="collapse <?php if ($this->uri->segment(2) === 'our_team') { echo 'in'; } ?>">
											<li <?php if ($this->uri->segment(2) === 'our_team' && $this->uri->segment(3) !== 'add') { echo 'class="active-link"'; } ?>><a href="<?php echo admin_url() . 'our_team'; ?>">View Our Team</a></li>
											<li <?php if ($this->uri->segment(2) === 'our_team' && $this->uri->segment(3) === 'add') { echo 'class="active-link"'; } ?>><a href="<?php echo admin_url() . 'our_team/add'; ?>">Add Our Team</a></li>
										</ul>
									</li>


									<!--Terms and Conditions  Management-->
									<li <?php if ($this->uri->segment(2) === 'terms') { echo 'class="active-link"'; } ?>>
										<a href="<?php echo admin_url() . 'terms'; ?>">
											<i class="fa fa-mars"></i>
											<span class="menu-title">
												<strong>Terms and Conditions</strong>
											</span>
										</a>
										<!--Submenu-->
										<ul class="collapse <?php if ($this->uri->segment(2) === 'terms') { echo 'in'; } ?>">
											<li <?php if ($this->uri->segment(2) === 'terms' && $this->uri->segment(3) !== 'add') { echo 'class="active-link"'; } ?>><a href="<?php echo admin_url() . 'terms'; ?>">View T&amp;C</a></li>
											<li <?php if ($this->uri->segment(2) === 'terms' && $this->uri->segment(3) === 'add') { echo 'class="active-link"'; } ?>><a href="<?php echo admin_url() . 'terms/add'; ?>">Add T&amp;C</a></li>
										</ul>
									</li>
									
									<!--News Management-->
									<!--<li <?php if ($this->uri->segment(2) === 'news') { echo 'class="active-link"'; } ?>>
										<a href="<?php echo admin_url() . 'news'; ?>">
											<i class="fa fa-newspaper-o"></i>
											<span class="menu-title">
												<strong>News</strong>
											</span>
										</a>
										<ul class="collapse <?php if ($this->uri->segment(2) === 'news') { echo 'in'; } ?>">
											<li <?php if ($this->uri->segment(2) === 'news' && $this->uri->segment(3) != 'add') { echo 'class="active-link"'; } ?>><a href="<?php echo admin_url() . 'news'; ?>">View List</a></li>
											<li <?php if ($this->uri->segment(2) === 'news' && $this->uri->segment(3) == 'add') { echo 'class="active-link"'; } ?>><a href="<?php echo admin_url() . 'news/add'; ?>">Add New</a></li> 
											</ul>
									</li> -->
									
									<!--Testimonials Management-->
									<li <?php if ($this->uri->segment(2) === 'testimonials') { echo 'class="active-link"'; } ?>>
										<a href="<?php echo admin_url() . 'testimonials'; ?>">
											<i class="fa fa-navicon"></i>
											<span class="menu-title">
												<strong>Testimonials</strong>
											</span>
										</a>
										
									</li>
									
									<!--Newsleters Management-->
									<li <?php if ($this->uri->segment(3) === 'newsletter') { echo 'class="active-link"'; } ?>>
										<a href="<?php echo admin_url() . 'admin/newsletter'; ?>">
											<i class="fa fa-envelope"></i>
											<span class="menu-title">
												<strong>Newsletter</strong>
											</span>
										</a>										
									</li>

									<!--Banner Management-->
									<!--<li <?php if ($this->uri->segment(2) === 'banners') { echo 'class="active-link"'; } ?>>
										<a href="<?php echo admin_url() . 'banners'; ?>">
											<i class="fa fa-picture-o"></i>
											<span class="menu-title">
												<strong>Banner Management</strong>
											</span>
										</a>
										<ul class="collapse <?php if ($this->uri->segment(2) === 'banners') { echo 'in'; } ?>">
											<li <?php if ($this->uri->segment(2) === 'banners' && $this->uri->segment(3) !== 'add') { echo 'class="active-link"'; } ?>><a href="<?php echo admin_url() . 'banners'; ?>">Banners</a></li>
										</ul>
									</li>-->
									
									
						
									
								<!--================================-->
								<!--End widget-->

							</div>
						</div>
					</div>
					<!--================================-->
					<!--End menu-->

				</div>
			</nav>
	<style>
		li{ 
			font-size: small;
		}
	</style>
			<!--===================================================-->
			<!--END MAIN NAVIGATION-->
