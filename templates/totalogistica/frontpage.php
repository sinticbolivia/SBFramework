<?php lt_get_header('fp'); ?>
<div class="clearfix"></div>
<!-- About start-->
<section class="about-1 pt-120 spb-1">
	<div class="container pl-xs-15 pr-xs-15 pl-0 pr-0">
		<div class="pt-sm-30 pl-xs-0 pr-xs-0 col-md-6 pt-sm-30 pl-xs-0 pr-xs-0 col-md-offset-1 pl-0 pr-0 about-1-right col-md-push-5">
                    <div class="section-head">
                        <img src="<?php print TEMPLATE_URL; ?>/img/section-sep-1.png" alt="">
                        <p class="header"><?php _e('About Us', 'tl'); ?></p>
                        <h1 class="header-text"><?php _e('The Main Features', 'tl'); ?></h1>
                    </div>
                    <div class="about-1-right-content pl-60">
                        <div class="about-1-right-content-item">
                            <i class="icon-engineer"></i>
                            <h2 class="section-sub-head c-3"><?php _e('Expert Staff', 'tl'); ?></h2>
                            <p class="text">Holy grail twitter vesting period termsheet supply chain partner network gamification scrum project hackathon backing. Ownership founders iPhone entrepreneur. </p>
                        </div>
                        <div class="about-1-right-content-item">
                            <i class="icon-planet-earth"></i>
                            <h2 class="section-sub-head c-3"><?php _e('Worldwide Locations', 'tl'); ?></h2>
                            <p class="text">Holy grail twitter vesting period termsheet supply chain partner network gamification scrum project hackathon backing. Ownership founders iPhone entrepreneur. </p>
                        </div>
                        <div class="about-1-right-content-item">
                            <i class="icon-archive"></i>
                            <h2 class="section-sub-head c-3"><?php _e('Huge Storage', 'tl'); ?></h2>
                            <p class="text pb-0">Holy grail twitter vesting period termsheet supply chain partner network gamification scrum project hackathon backing. Ownership founders iPhone entrepreneur. </p>
                        </div>
                    </div>
                </div>
                <!-- About-left-->
                <div class="about-1-left pt-sm-30 pl-xs-0 pr-xs-0 col-md-5 pt-5 pl-0 pr-0 col-md-pull-7">
                    <div class="about-1-left-img">
                        <img src="<?php print TEMPLATE_URL; ?>/img/about/about-1.jpg" alt="">
                    </div>
                    <div class="about-1-left-content pt-40">
                        <p class="text">Equity first mover advantage handshake interaction design infographic backing stealth market growth hacking founders paradigm shift. Advisor learning curve interaction design analytics launch party facebook validation infographic.</p>
                        <a href="#" class="about-1-left-read-more c-3 ls-15 db lh-10 ff-bl text-up">
                        	<?php _e('read more', 'tl'); ?><i class="fa fa-long-arrow-right lh-10 c-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </section>
        <!-- About end-->
        <div class="clearfix"></div>
        <!-- Calculate start-->
        <section class="calculate-1 pt-120 pb-120 bg-color-1">
            <div class="container pl-xs-15 pr-xs-15 pl-0 pr-0">
                <div class="section-head">
                    <img src="<?php print TEMPLATE_URL; ?>/img/section-sep-1.png" alt="">
                    <p class="header"><?php _e('calculate', 'tl'); ?></p>
                    <h1 class="header-text"><?php _e('total delivery cost', 'tl'); ?></h1>
                </div>
                <p class="text-up ff-n ls-15 fz-14 lh-10 c-3"><?php _e('find out the approximate cost of delivery of your shipment', 'tl'); ?></p>
                <div class="calculate-1-left pt-sm-30 pl-xs-0 pr-xs-0 col-md-7 pl-0 pr-40">
                    <form action="get">
                        <div class="pl-xs-0 pr-xs-0 col-sm-6 pl-0 pr-15">
                            <p class="calculate-1-form-head"><?php _e('distance', 'tl'); ?></p>
                            <input type="text" id="calculate-1-distance">
                        </div>
                        <div class="pl-xs-0 pr-xs-0 col-sm-6 pl-15 pr-0">
                            <p class="calculate-1-form-head"><?php _e('weight', 'tl'); ?></p>
                            <input type="text" id="calculate-1-weight">
                        </div>
                        <div class="clearfix"></div>
                        <div class="pl-xs-0 pr-xs-0 col-sm-4 pl-0 pr-20">
                            <p class="calculate-1-form-head"><?php _e('height', 'tl'); ?></p>
                            <input type="text" id="calculate-1-height">
                        </div>
                        <div class="pl-xs-0 pr-xs-0 col-sm-4 pl-10 pr-10">
                            <p class="calculate-1-form-head"><?php _e('width', 'tl'); ?></p>
                            <input type="text" id="calculate-1-width">
                        </div>
                        <div class="pl-xs-0 pr-xs-0 col-sm-4 pl-20 pr-0">
                            <p class="calculate-1-form-head"><?php _e('length', 'tl'); ?></p>
                            <input type="text" id="calculate-1-length">
                        </div>
                        <div class="clearfix"></div>
                        <div class="pl-xs-0 pr-xs-0 col-sm-4 pl-0 pr-15">
                            <p class="calculate-1-form-head c-3 pb-30">extra services</p>
                            <label for="checkbox-one" class="calculate-1-label">
                                <input type="checkbox" id="checkbox-one">express delivery</label>
                            <div class="clearfix"></div>
                            <label for="checkbox-two" class="calculate-1-label">
                                <input type="checkbox" id="checkbox-two">insurance</label>
                            <div class="clearfix"></div>
                            <label for="checkbox-three" class="calculate-1-label">
                                <input type="checkbox" class="mb-0" id="checkbox-three">packaging</label>
                            <div class="clearfix"></div>
                        </div>
                        <div class="pl-xs-0 pr-xs-0 col-sm-4 pl-15 pr-0">
                            <p class="calculate-1-form-head c-3 pb-30">fragile</p>
                            <label for="radio-one" class="calculate-1-label">
                                <input type="radio" name="radio" id="radio-one" checked>Yes</label>
                            <div class="clearfix"></div>
                            <label for="radio-two" class="calculate-1-label">
                                <input type="radio" name="radio" class="mb-0" id="radio-two">no</label>
                            <div class="clearfix"></div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="pl-xs-0 pr-xs-0 col-sm-12 pl-0 pr-0">
                            <p class="calculate-1-form-head">total</p>
                            <input type="text" id="calculate-1-total" value="$20">
                        </div>

                    </form>
                </div>
                <div class="calculate-1-right pt-sm-30 pl-xs-0 pr-xs-0 col-md-5 pl-0 pr-0 pos-r">
                    <div class="calculate-1-right-abs">
                        <img src="<?php print TEMPLATE_URL; ?>/img/resources/calculate-1.jpg" alt="">
                    </div>
                </div>
            </div>
        </section>
        <!-- Calculate end-->
        <div class="clearfix"></div>
        <!-- Services start-->
        <section class="services-1 pt-120 pb-120">
            <div class="container pl-xs-15 pr-xs-15 pl-0 pr-0">
                <div class="section-head center">
                    <img src="<?php print TEMPLATE_URL; ?>/img/section-sep-2.png" alt="">
                    <p class="header center"><?php _e('our services', 'tl'); ?></p>
                    <h1 class="header-text center"><?php _e('what we can do for you', 'tl'); ?></h1>
                </div>
                <div class="services-1-content">
                    <!-- services-1 1st row-->
                    <div class="services-1-content-item pt-xs-30 pl-xs-0 pr-xs-0 col-md-4 col-sm-6 pt-sm-50 pl-0 pr-0">
                        <div class="services-1-content-1st-col ml-sm-30 ml-xs-70 mr-xs-15 mr-sm-30 ml-xs-70 ml-xxs-30 mr-xxs-0 mr-xs-15 pl-80 pl-xs-60 pr-25 pr-xs-10 pos-r">
                            <h2 class="section-sub-head"><?php _e('transportation', 'tl'); ?></h2>
                            <p class="text"><?php _e('Direct mailing hypothese channels return on investment seed money.', 'tl'); ?></p>
                            <i class="icon-airplane"></i>
                        </div>
                    </div>
                    <div class="services-1-content-item pt-xs-30 pl-xs-0 pr-xs-0 col-md-4 col-sm-6 pt-sm-50 pl-0 pr-0">
                        <div class="services-1-content-2nd-col ml-sm-30 ml-xs-70 mr-xs-15 mr-sm-30 ml-xs-70 ml-xxs-30 mr-xxs-0 mr-xs-15 pl-80 pl-xs-60 pr-25 pr-xs-10 pos-r">
                            <h2 class="section-sub-head"><?php _e('logistics', 'tl'); ?></h2>
                            <p class="text"><?php _e('Direct mailing hypothese channels return on investment seed money.', 'tl'); ?></p>
                            <i class="icon-transport-6"></i>
                        </div>
                    </div>
                    <div class="services-1-content-item pt-xs-30 pl-xs-0 pr-xs-0 col-md-4 col-sm-6 pt-sm-50 pl-0 pr-0">
                        <div class="services-1-content-3rd-col ml-sm-30 ml-xs-70 mr-xs-15 mr-sm-30 ml-xs-70 ml-xxs-30 mr-xxs-0 mr-xs-15 pl-80 pl-xs-60 pr-25 pr-xs-10 pos-r">
                            <h2 class="section-sub-head"><?php _e('package &amp; storage', 'tl'); ?></h2>
                            <p class="text"><?php _e('Direct mailing hypothese channels return on investment seed money.', 'tl'); ?></p>
                            <i class="icon-business"></i>
                        </div>
                    </div>
                    <!-- services-1 2nd row-->
                    <div class="services-1-content-item pt-xs-30 pl-xs-0 pr-xs-0 col-md-4 col-sm-6 pl-0 pt-50 pr-0">
                        <div class="services-1-content-1st-col ml-sm-30 ml-xs-70 mr-xs-15 mr-sm-30 ml-xs-70 ml-xxs-30 mr-xxs-0 mr-xs-15 pl-80 pl-xs-60 pr-25 pr-xs-10 pos-r">
                            <h2 class="section-sub-head"><?php _e('Warehousing', 'tl'); ?></h2>
                            <p class="text"><?php _e('Direct mailing hypothese channels return on investment seed money.', 'tl'); ?></p>
                            <i class="icon-transport-1"></i>
                        </div>
                    </div>
                    <div class="services-1-content-item pt-xs-30 pl-xs-0 pr-xs-0 col-md-4 col-sm-6 pl-0 pt-50 pr-0">
                        <div class="services-1-content-2nd-col ml-sm-30 ml-xs-70 mr-xs-15 mr-sm-30 ml-xs-70 ml-xxs-30 mr-xxs-0 mr-xs-15 pl-80 pl-xs-60 pr-25 pr-xs-10 pos-r">
                            <h2 class="section-sub-head"><?php _e('Cargo', 'tl'); ?></h2>
                            <p class="text">Direct mailing hypothese channels return on investment seed money.</p>
                            <i class="icon-package"></i>
                        </div>
                    </div>
                    <div class="services-1-content-item pt-xs-30 pl-xs-0 pr-xs-0 col-md-4 col-sm-6 pl-0 pt-50 pr-0">
                        <div class="services-1-content-3rd-col ml-sm-30 ml-xs-70 mr-xs-15 mr-sm-30 ml-xs-70 ml-xxs-30 mr-xxs-0 mr-xs-15 pl-80 pl-xs-60 pr-25 pr-xs-10 pos-r">
                            <h2 class="section-sub-head"><?php _e('Door to Door Delivery', 'tl'); ?></h2>
                            <p class="text">Direct mailing hypothese channels return on investment seed money.</p>
                            <i class="icon-box"></i>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Services end-->
        <div class="clearfix"></div>
        <!-- Testimonials start-->
        <section class="testimonial-1 pt-120 pb-120 bg-color-3">
            <div class="container pl-xs-15 pr-xs-15 pl-0 pr-0">
                <div class="section-head center">
                    <img src="<?php print TEMPLATE_URL; ?>/img/section-sep-2.png" alt="">
                    <p class="header center"><?php _e('testimonials', 'tl'); ?></p>
                    <h1 class="header-text c-5 center"><?php _e('They trust us', 'tl'); ?></h1>
                </div>
                <div class="testimonial-slider-wrapper">
                    <div class="testimonial-slider owl-carousel">
                        <div class="testimonial-slider-item">
                            <i class="fa fa-quote-left"></i>
                            <p class="text center pl-120 pl-xs-20 pr-xs-20 pr-120 ">
                            	Bandwidth leverage freemium buzz. Buyer startup funding equity iPad hypotheses. Metrics handshake 
                            	business-to-consumer ramen scrum project research demonstrate gamification pivot. Research &amp; development 
                            	crowdfunding market scrum project monetization product management termsheet beta stock stealth assets technology 
                            	sales. Assets success ecosystem facebook iPad equity.
                            </p>
                            <h5 class="ff-sb ls-15 lh-10 center pb-20 c-5 testimonial-name text-up fz-14">Michael Houlberg</h5>
                            <p class="center fz-14 lh-10 ls-15 ff-l c-4">CEO of the Company</p>
                        </div>
                        <div class="testimonial-slider-item">
                            <i class="fa fa-quote-left"></i>
                            <p class="text center pl-120 pl-xs-20 pr-xs-20 pr-120 ">Bandwidth leverage freemium buzz. Buyer startup funding equity iPad hypotheses. Metrics handshake business-to-consumer ramen scrum project research demonstrate gamification pivot. Research &amp; development crowdfunding market scrum project monetization product management termsheet beta stock stealth assets technology sales. Assets success ecosystem facebook iPad equity.</p>
                            <h5 class="ff-sb ls-15 lh-10 center pb-20 c-5 testimonial-name text-up fz-14">Michael Houlberg</h5>
                            <p class="center fz-14 lh-10 ls-15 ff-l c-4">CEO of the Company</p>
                        </div>
                        <div class="testimonial-slider-item">
                            <i class="fa fa-quote-left"></i>
                            <p class="text center pl-120 pl-xs-20 pr-xs-20 pr-120 ">Bandwidth leverage freemium buzz. Buyer startup funding equity iPad hypotheses. Metrics handshake business-to-consumer ramen scrum project research demonstrate gamification pivot. Research &amp; development crowdfunding market scrum project monetization product management termsheet beta stock stealth assets technology sales. Assets success ecosystem facebook iPad equity.</p>
                            <h5 class="ff-sb ls-15 lh-10 center pb-20 c-5 testimonial-name text-up fz-14">Michael Houlberg</h5>
                            <p class="center fz-14 lh-10 ls-15 ff-l c-4">CEO of the Company</p>
                        </div>
                    </div>
                    <div class="owl-dots-wrapper">
                        <div id="owl-dots">
                            <div class="owl-dot active"></div>
                            <div class="owl-dot"></div>
                            <div class="owl-dot"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- Testimonials end-->
        <div class="clearfix"></div>
        <!-- News start-->
        <section class="news-1 pt-120 pb-120">
            <div class="container pl-xs-15 pr-xs-15 pl-0 pr-0">
                <div class="pt-sm-30 pl-xs-0 pr-xs-0 col-md-6 pl-0 pr-md-15 pr-sm-0">
                    <div class="section-head">
                        <img src="<?php print TEMPLATE_URL; ?>/img/section-sep-1.png" alt="">
                        <p class="header"><?php _e('our blog', 'tl'); ?></p>
                        <h1 class="header-text"><?php _e('latest news', 'tl'); ?></h1>
                    </div>
                    <div class="news-1-item pb-xxs-30">
                        <a href="news-single.html" class="image db mb-40">
                            <img src="<?php print TEMPLATE_URL; ?>/img/news/news-1.jpg" alt="">
                        </a>
                        <div class="news-1-item-content">
                            <a href="#" class="news-1-date c-1">07<span>/Aug</span></a>
                            <p class="text text-up c-3">Logistic Leads the Way on e-AWB Implementation</p>
                            <a href="news-single.html" class="news-1-item-cont c-3 ls-15 dib lh-10 ff-bl text-up">continue<i class="fa fa-long-arrow-right lh-10 c-1"></i></a>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="news-1-item pb-xxs-30">
                        <a href="news-single.html" class="image db">
                            <img src="<?php print TEMPLATE_URL; ?>/img/news/news-2.jpg" alt="">
                        </a>
                        <div class="news-1-item-content">
                            <a href="#" class="news-1-date c-1">13<span>/Aug</span></a>
                            <p class="text text-up c-3">Air Cargo May Become Short-term Solution for Shippers</p>
                            <a href="news-single.html" class="news-1-item-cont c-3 ls-15 dib lh-10 ff-bl text-up">continue<i class="fa fa-long-arrow-right lh-10 c-1"></i></a>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
                <div class="pt-sm-30 pl-xs-0 pr-xs-0 col-md-6 pr-0 pl-md-15 pr-sm-0">
                    <div class="news-1-right quick-quote pl-40 pr-40 pt-50 pb-50">
                        <h3 class="quick-quote-head"><?php _e('Request a Quick Quote', 'tl'); ?></h3>
                        <p class="text">Research &amp; development crowdfunding market project monetization product management termsheet beta stock stealth assets technology sales.</p>
                        <form action="get" class="quick-quote-form">
                            <div class="pl-xs-0 pr-xs-0 col-sm-6 pl-0 pr-sm-10">
                                <input type="text" class="quick-quote-name" placeholder="<?php _e('Name', 'tl'); ?>">
                            </div>
                            <div class="pl-xs-0 pr-xs-0 col-sm-6 pr-0 pl-sm-10">
                                <input type="text" class="quick-quote-sub" placeholder="<?php _e('Subject', 'tl'); ?>">
                            </div>
                            <div class="pl-xs-0 pr-xs-0 col-sm-6 pl-0 pr-sm-10">
                                <div class="pl-xs-0 pr-xs-0 col-sm-12 pl-0 pr-0">
                                    <input type="email" class="quick-quote-mail" placeholder="<?php _e('Email', 'tl'); ?>">
                                </div>
                                <div class="pl-xs-0 pr-xs-0 col-sm-12 pl-0 pr-0">
                                    <select name="package" id="quick-quote-select" class="quick-quote-select">
                                        <option class="quick-quote-package-option" value="package-1"><?php _e('Package-1', 'tl'); ?></option>
                                        <option class="quick-quote-package-option" value="package-2"><?php _e('Package-2', 'tl'); ?></option>
                                        <option class="quick-quote-package-option" value="package-3"><?php _e('Package-3', 'tl'); ?></option>
                                    </select>
                                    <label for="quick-quote-select" class="quick-quote-select-label"></label>
                                </div>
                            </div>
                            <div class="pl-xs-0 pt-xs-20 pr-xs-0 col-sm-6 pr-0 pl-sm-10">
                                <textarea class="quick-quote-message" placeholder="<?php _e('Message', 'tl'); ?>"></textarea>
                            </div>
                            <div class="clearfix"></div>
                            <button type="submit" class="default-btn db mt-40"><?php _e('Send Message', 'tl'); ?></button>
                        </form>
                    </div>
                </div>
            </div>
        </section><!-- News end-->
        <div class="clearfix"></div>
        <?php lt_include_template('sections/clients'); ?>
<?php lt_get_footer(); ?>