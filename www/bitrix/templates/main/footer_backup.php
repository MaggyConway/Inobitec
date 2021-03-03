			<?
            $сache = Bitrix\Main\Data\Cache::createInstance();

            if($сache -> initCache(36000, 'youtube_video_list')) 
            { 
                $resultYoutube = $сache -> getVars(); 
            } 
            elseif($сache -> startDataCache())
            { 
                if(CModule::IncludeModule('iblock')){

                    $preItems = CIBlockElement::GetList(
                        array('PUBLISHED' => 'desc' ),
                        array('IBLOCK_CODE' =>'youtube_video_list', "ACTIVE" => "Y"),
                        false,
                        false,
                        array('ID', 'NAME', 'PROPERTY_VIDEOURL', 'PROPERTY_THUMBNAIL', 'PROPERTY_VIDEOID', 'PROPERTY_PUBLISHED', "PREVIEW_PICTURE")
                    );

                    while($arItem = $preItems -> GetNext()){
                        if($arItem['PREVIEW_PICTURE']){
                            if($img = CFile::GetFileArray($arItem['PREVIEW_PICTURE'])){
                                $arItem['PREVIEW_PICTURE'] = $img;
                            }
                        }

                        $resultYoutube[] = $arItem;
                    }

                }else{

                    $isInvalid = true;
                }

                if ($isInvalid) 
                { 
                    $cache->abortDataCache(); 
                } 
                $сache->endDataCache($resultYoutube); 
            }
            
			if(defined("ERROR_404") && ERROR_404)
			{
				$error_404 = true;
			}
			if (!$is_main){?>
                    
					</div><!-- END :: PAGE-CONTENT -> WR -->
                <?if(!$newPageCSS):?>    
				</div><!-- END :: PAGE-CONTENT -->
                <?endif;?>
			</div><!-- END :: CONTENT -->
			<?}?>
              <div class="popup" ng-controller="popupController" id="basketAlertTwo">
                <div class="popup-main">
                    <div class="close">
                        <svg width="28" height="28" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="10.0791" width="1.67695" height="14.2541" transform="rotate(45 10.0791 0)" fill="#C4C4C4"/>
                            <rect x="11.8579" y="10.0791" width="1.67695" height="14.2541" transform="rotate(135 11.8579 10.0791)" fill="#C4C4C4"/>
                        </svg>
                    </div>
                    <p class="authorization"><?=GetMessage("CUSTOM.FOOTER.POPUP.ITEM_DELTE")?></p>
                    <form>
                        <p class="pass-success-text"><?=GetMessage("CUSTOM.FOOTER.POPUP.CONFIRM_REMOVE")?></p>
                        <div class="popup-main_row">
                            <button class="btn-blue btn-red authorization-button" ng-click='defaultDelGoodPopup()'><?=GetMessage("CUSTOM.FOOTER.POPUP.YES")?></button>
                            <button class="btn-blue authorization-button"   ng-click="closePopup('basketAlertTwo')"><?=GetMessage("CUSTOM.FOOTER.POPUP.CANCEL")?></button>
                        </div>
                    </form>
                </div>
              </div>
            <?
				if (
					CSIte::InDir('/personal/') || CSIte::InDir('/eng/personal/') ||
					CSIte::InDir('/login/') || CSIte::InDir('/eng/login/') ||
					CSIte::InDir('/search/') || CSIte::InDir('/eng/search/')
				) 
					$no_f_links = true;

				if (!$no_f_links) {
			?>
			<div class="link-block  ">
				<div class="wr">
					<a href="<?=SITE_DIR?>products/" class="link-block__item i1">
						<span class="ico"><img src="<?=SITE_TEMPLATE_PATH?>/img/f_serv_ico_1.png" width="159" height="132" alt="Наши продукты" /></span>
						<span class="text">
							<?=$MESS[SITE_ID]['FOOTER_PRODUCTS']?>
						</span>
						<span class="cl"></span>
					</a>
					<a href="<?=SITE_DIR?>downloads/" class="link-block__item i2">
						<span class="ico"><img src="<?=SITE_TEMPLATE_PATH?>/img/f_serv_ico_2.png" width="159" height="132" alt="Скачать пробную версию" /></span>
						<span class="text">
							<?=$MESS[SITE_ID]['FOOTER_DOWNLOAD']?>
						</span>
						<span class="cl"></span>
					</a>
					<div class="cl"></div>
				</div>
			</div>
			<?
				}
			?>
			<!-- START :: FOOTER -->
			<?if(!$error_404){?>
			<div class="footer">
                <!-- START :: SUBSCRIBE -->
                <!--div class="subscribe">
                    <div class="subscribe__main">
                        <p>подпишитесь на рассылку и следите за обновлениями</p>
                        <form>
                            <div class="subscribe__main-column">
                                <label for="subscribe">Email</label>
                                <input id="subscribe">
                            </div>
                            <button type="submit">подписаться</button>
                            <div class="subscribe__main-column">
                                <div class="verification">
                                    <label class="checkbox">
                                        <input type="checkbox">
                                        <div class="checkbox_button"></div>
                                        <span>Я соглашаюсь с <a href="#">Лицензионным договором-офертой</a> и <a href="/about/agreement-for-the-processing-of-personal-data.php" target="_blanck">Обработкой персональных данных</a></span>
                                    </label>
                                </div>
                            </div>
                        </form>
                    </div>
                </div-->
                <!-- END :: SUBSCRIBE -->
                <div class="fb-youtube-block">
                  <div id="unix" data-lang="<?=(SITE_ID == 's1')?"ru":"en"?>" data-ycp_title="Inobitec Company" data-ycp_channel="UCskaF1EhpIFh1jKVSCl51Ew">
                      <div class="video">
                          <iframe width="100%" height="100%" src="https://www.youtube.com/embed/IpvC-m41k0k" frameborder="0"
                                  allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen>
                          </iframe>
                      </div>
                  </div>
                    <div class="fb-page-block">
                        <div class="fb-page" data-href="https://www.facebook.com/Inobitec/" data-tabs="timeline" data-width="555" data-height="400" data-small-header="true" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true"><blockquote cite="https://www.facebook.com/Inobitec/" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/Inobitec/">Inobitec.com</a></blockquote></div>
                    </div>
                </div>
                
				<div class="footer__menu">
					<?
					$APPLICATION->IncludeComponent( // MENU.TOP COMPONENT
						"bitrix:menu",
						"common__down",
						Array(
							"ALLOW_MULTI_SELECT" => "N",
							"CHILD_MENU_TYPE" => "left",
							"DELAY" => "N",
							"MAX_LEVEL" => "1",
							"MENU_CACHE_GET_VARS" => array(""),
							"MENU_CACHE_TIME" => "3600",
							"MENU_CACHE_TYPE" => "A",
							"MENU_CACHE_USE_GROUPS" => "Y",
							"ROOT_MENU_TYPE" => "top",
							"USE_EXT" => "N"
						)
					);?>
				</div>		
				<div class="footer__copy">
					<?
					// включаемая область для раздела
					$APPLICATION->IncludeFile(SITE_DIR."/includes/copy.php", Array(), Array(
					    "MODE"      => "html",                                           // будет редактировать в веб-редакторе
					    "NAME"      => "Редактирование включаемой области раздела",      // текст всплывающей подсказки на иконке
					    "TEMPLATE"  => "section_include_template.php"                    // имя шаблона для нового файла
					    ));
					?>
				</div>		
			</div>
			<?}?>
			<div class="mobile-menu">
				<div class="inn">
					<a class="mobile-menu__logo" href="/"><img src="<?=SITE_TEMPLATE_PATH?>/img/svg/logo_white.svg" width="225" height="87" alt="ИНОБИТЕК / Инновации в медицине"></a>	
					<a href="#" class="close">&nbsp;</a>
						<ul class="mobile-menu__lang">
						<?if(SITE_ID == 's1'){?>
						<li><span>RUS</span></li>
						<li><a href="/eng">ENG</a></li>
						<?}else{?>
						<li><a href="/">RUS</a></li>
						<li><span>ENG</span></li>
						<?}?>
					</ul>
					<?
					$APPLICATION->IncludeComponent( // MENU.TOP COMPONENT ::  ОСНОВНОЕ МЕНЮ САЙТА
						"bitrix:menu",
						"common__top__basket",
						array(
							"ALLOW_MULTI_SELECT" 	=> "N",
							"CHILD_MENU_TYPE" 		=> "left",
							"DELAY" 				=> "N",
							"MAX_LEVEL" 			=> "1",
							"MENU_CACHE_GET_VARS" 	=> array(""),
							"MENU_CACHE_TIME" 		=> "3600",
							"MENU_CACHE_TYPE" 		=> "A",
							"MENU_CACHE_USE_GROUPS" => "Y",
							"ROOT_MENU_TYPE" 		=> "top",
							"USE_EXT" 				=> "N"
						)
					);
					?>
					<div class="mobile-menu__phone">+7-920-405-67-43</div>
					<div class="mobile-menu__email"><a href="mailto:market@inobitec.com">market@inobitec.com</a></div>
				</div>
			</div>
			
			<div class="product-features__popup">
				<div class="inn">
				</div>
				<a href="#" class="close"></a>
			</div>
        <?
          if(strlen($APPLICATION->GetProperty('showPreloader')) > 0) {
              if($APPLICATION->GetProperty('showPreloader') == "YES")
                $cartPreloader = true;
          }
        
            if (!$cartPreloader) {
        ?>    
			<div class="preloader">
				<div class="inn">
					<img src="<?=SITE_TEMPLATE_PATH?>/img/preloader.gif" alt=""/>
					<b><?=GetMessage("CUSTOM_FOOTER_WAIT")?></b>
					<?=GetMessage("CUSTOM_FOOTER_WAIT_DESCRIPTION")?>
				</div>
			</div>
        <?
            }else{
        ?>    
            <div class="preloader">
				<div class="inn">
					<img src="<?=SITE_TEMPLATE_PATH?>/img/preloader.gif" alt=""/>
					<b><?=GetMessage("CUSTOM_FOOTER_WAIT")?></b>
					<?=GetMessage("CUSTOM_FOOTER_WAIT_DESCRIPTION_GOODS")?>
				</div>
			</div>
            <script>
              $('.preloader').show();
            </script>
        <?
            }
        ?>
			<!-- END :: FOOTER -->
		</div>
		<?if (!$USER->IsAuthorized()): ?>
			<?$APPLICATION->IncludeComponent("bitrix:system.auth.authorize","custom",Array(
		     		"PROFILE_URL" => "/personal/",
		     		"POPUP" => "Y" 
	     		)
			);?>
			<?$APPLICATION->IncludeComponent("bitrix:system.auth.forgotpasswd","custom",Array(
		     		"POPUP" => "Y" 
	     		)
			);?>
		<?endif;?>
		<!-- Go to www.addthis.com/dashboard to customize your tools --> <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-4def994715dc2cfa"></script> 
        <script>
          $(document).on('click', 'a.js-RestoreRedirect', function(e){
            e.preventDefault();

            var $paramVal = gup('USER_LOGIN',$(this).attr('href'));
            if($paramVal == undefined)
              var $url = $(this).attr('href') +"&USER_LOGIN=" + $("#userLogin").val();
            else{
              var $url = $(this).attr('href');
              $url = $url.replace(/(USER_LOGIN=).*?(&|$)/,'$1' + $("#userLogin").val() + '$2');
            }
            setTimeout(function(){document.location.href = $url;},250);
        });
        function gup( name, url ) {
           if (!url) url = location.href;
           name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
           var regexS = "[\\?&]"+name+"=([^&#]*)";
           var regex = new RegExp( regexS );
           var results = regex.exec( url );
           return results == null ? null : results[1];
        }
        </script>
	</body>
</html>
