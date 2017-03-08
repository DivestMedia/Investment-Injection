<!-- 
				SLIDER

				Classes:
					.fullheight = full height slider
			-->
<section id="slider" class="height-600 parallax-slider">
    <!--
					SWIPPER SLIDER PARAMS
					
					data-effect="slide|fade|coverflow"
					data-autoplay="2500|false" 						(remove to disable autoplay)
				-->
    <div class="swiper-container" data-effect="fade" data-autoplay="false">
        <div class="swiper-wrapper">
            <!-- SLIDE 1 -->
            <div class="swiper-slide" style="background-image: url('<?=get_template_directory_uri();?>/assets/images/demo/vision-min.jpg');">
                <div class="overlay dark-5">
                    <!-- dark overlay [1 to 9 opacity] -->
                </div>
                <div class="display-table">
                    <div class="display-table-cell vertical-align-middle">
                        <div class="container">
                            <table class="fullwidth slider-table">
                                <tr>
                                    <td class="white-bg wow fadeInLeft" data-wow-delay="0.1s">
                                        <a class="pull-right size-80 primary-dark-color" href="#"><i class="fa fa-angle-right"></i></a>
                                        <span class="primary-color weight-700">days to go</span>
                                        <h1 class="primary-dark-color size-100 font-lato weight-700 margin-bottom-0">27</h1>
                                    </td>
                                    <td data-wow-delay="0.5s" class="wow fadeIn" rowspan="2" style="background-image: url(<?=get_template_directory_uri();?>/assets/images/demo/fashion/500x750/u-min.jpg); background-size:cover; width:300px;"></td>
                                    <td data-wow-delay="0.3s" class="secondary-bg wow fadeInDown">
                                        <h3 class="margin-bottom-0  margin-top-50">LOST EMBER</h3>
                                        <p>By: Mooneye Studios</p>
                                        <p>Video Games</p>
                                    </td>
                                    <td data-wow-delay="0.8s" rowspan="2" class="secondary-dark-bg wow fadeInRight">
                                        <p class="margin-bottom-10">
                                            Explore the remains of a fallen empire from a wolf's perspective! Control every animal you encounter to uncover your destiny.
                                        </p>
                                        <a class="btn btn-primary secondary-bg btn-lg noradius" href="#">VIEW CAMPAIGN</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td data-wow-delay="1.2s" class="primary-bg wow fadeInRight">
                                        <h1 class="yellowish-color font-lato size-80 weight-700 margin-bottom-0">89%</h1>
                                        <span class="white-text weight-700">funded</span>
                                    </td>
                                    <td data-wow-delay="1s" class="primary-dark-bg uppercase font-lato wow fadeInUp">
                                        <h1 class="primary-color font-lato weight-700 margin-bottom-10">$127,000</h1>
                                        <span class="white-text weight-700">Pledged goal</span>
                                        <br/>
                                        <span class="white-text weight-700">$150,000</span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /SLIDE 1 -->
            <!-- SLIDE 3 -->
            <div class="swiper-slide" style="background-image: url('<?=get_template_directory_uri();?>/assets/images/demo/vision-min.jpg');">
                <div class="overlay dark-4">
                    <!-- dark overlay [1 to 9 opacity] -->
                </div>
                <div class="display-table">
                    <div class="display-table-cell vertical-align-middle">
                        <div class="container">
                            <div class="row">
                                <div class="text-center col-md-8 col-xs-12 col-md-offset-2">
                                    <h1 class="bold font-raleway wow fadeInUp" data-wow-delay="0.4s">WELCOME TO <span>INVESTMENT INJECTION</span></h1>
                                    <p class="lead font-lato weight-300 hidden-xs wow fadeInUp" data-wow-delay="0.6s">Porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam.</p>
                                    <a class="btn btn-default btn-lg wow fadeIn noradius" data-wow-delay="1.5s" href="#">SIGN UP NOW</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /SLIDE 3 -->
        </div>
        <!-- Swiper Pagination -->
        <div class="swiper-pagination"></div>
        <!-- Swiper Arrows -->
        <div class="swiper-button-next"><i class="fa fa-angle-right"></i></div>
        <div class="swiper-button-prev"><i class="fa fa-angle-left"></i></div>
    </div>
</section>
<!-- /SLIDER -->
