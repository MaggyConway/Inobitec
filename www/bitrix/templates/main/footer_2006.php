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
				</div><!-- END :: PAGE-CONTENT -->
			</div><!-- END :: CONTENT -->
			<?}?>
			<?
				if (CSIte::InDir('/personal/') || CSIte::InDir('/eng/personal/')) 
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
                        <input type="checkbox">
                        <p class="verificationText">
                          Я соглашаюсь с
                          <a href="#">Лицензионным договором-офертой</a> и <a href="#">Обработкой персональных данных</a>
                        </p>
                      </div>
                    </form>
                  </div>
                </div-->
                <!-- END :: SUBSCRIBE -->
                <div class="fb-youtube-block">
                  <div id="unix" data-lang="<?=(SITE_ID == 's1')?"ru":"en"?>" data-ycp_title="Inobitec Company" data-ycp_channel="UCskaF1EhpIFh1jKVSCl51Ew"></div>
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
					<a class="mobile-menu__logo" href="/"><img src="<?=SITE_TEMPLATE_PATH?>/img/h-logo.png" width="225" height="87" alt="ИНОБИТЕК / Инновации в медицине"></a>	
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
					<b>Пожалуйста, подождите</b>
					Происходит<br />отправка данных формы<br />
					и приложенных файлов
				</div>
			</div>
        <?
            }else{
        ?>    
            <div class="preloader">
				<div class="inn">
					<img src="<?=SITE_TEMPLATE_PATH?>/img/preloader.gif" alt=""/>
					<b>Пожалуйста, подождите</b>
					Происходит<br />получение данных<br />
					о товарах
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
        
        
        
		<!-- Go to www.addthis.com/dashboard to customize your tools --> <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-4def994715dc2cfa"></script> 
	</body>
</html>
