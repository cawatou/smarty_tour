{$__ctx->addJs('/frontend/dropzone.js')}
{$__ctx->addJs('/frontend/jquery.rating.pack.js')}
{$__ctx->addJs('/frontend/form-feedback-hotel.js')}

{assign var="model" value=$__f->getModel()}

<form method="post" class="form form-feedback form-feedback-hotel" action="{$__f->getUrl()}">
    <div class="control-group{if $__f->e('feedback_user_name') !== null} has-error{/if}">
        <label for="{$__f->encode('feedback_user_name')}">
            Ваше имя:
            <span class="form-asterisk">*</span>
        </label>

        <div class="controls">
            <input type="text" class="input-text" name="{$__f->encode('feedback_user_name')}" value="{$model->getUserName()|escape}" id="{$__f->encode('feedback_user_name')}" />

            <div class="help-block error">
                Обязательное поле
            </div>
        </div>
    </div>

    <div class="control-group{if $__f->e('feedback_user_email') !== null} has-error{/if}">
        <label for="{$__f->encode('feedback_user_email')}">
            Ваш email:
            <span class="form-asterisk">*</span>
        </label>

        <div class="controls">
            <input type="text" class="input-text" name="{$__f->encode('feedback_user_email')}" value="{$model->getUserEmail()|escape}" id="{$__f->encode('feedback_user_email')}" />

            <div class="help-block error">
                {if $__f->e('feedback_user_email') == 'INVALID_FORMAT'}Некорректный адрес{else}Обязательное поле{/if}
            </div>
        </div>
    </div>

    <div class="control-group">
        <label>
            Оценки отеля:
            <span class="form-asterisk">*</span>
        </label>

        <div class="controls">
            <div class="subgroup clearfix">
                <div class="subgroup-part clearfix">
                    <span class="sublabel">Состояние номера</span>

                    <div class="rateBlock">
                        <input name="{$__f->encode('feedback_extended_rating_room')}"{if $model->getExtendedData('rating_room') == 1} checked="checked"{/if} value="1" type="radio" class="star"/>
                        <input name="{$__f->encode('feedback_extended_rating_room')}"{if $model->getExtendedData('rating_room') == 2} checked="checked"{/if} value="2" type="radio" class="star"/>
                        <input name="{$__f->encode('feedback_extended_rating_room')}"{if $model->getExtendedData('rating_room') == 3} checked="checked"{/if} value="3" type="radio" class="star"/>
                        <input name="{$__f->encode('feedback_extended_rating_room')}"{if $model->getExtendedData('rating_room') == 4} checked="checked"{/if} value="4" type="radio" class="star"/>
                        <input name="{$__f->encode('feedback_extended_rating_room')}"{if $model->getExtendedData('rating_room') == 5 || $model->getExtendedData('rating_room') === null} checked="checked"{/if} value="5" type="radio" class="star" />
                    </div>
                </div>

                <div class="subgroup-part clearfix">
                    <span class="sublabel">Пляж</span>

                    <div class="rateBlock">
                        <input name="{$__f->encode('feedback_extended_rating_beach')}"{if $model->getExtendedData('rating_beach') == 1} checked="checked"{/if} value="1" type="radio" class="star"/>
                        <input name="{$__f->encode('feedback_extended_rating_beach')}"{if $model->getExtendedData('rating_beach') == 2} checked="checked"{/if} value="2" type="radio" class="star"/>
                        <input name="{$__f->encode('feedback_extended_rating_beach')}"{if $model->getExtendedData('rating_beach') == 3} checked="checked"{/if} value="3" type="radio" class="star"/>
                        <input name="{$__f->encode('feedback_extended_rating_beach')}"{if $model->getExtendedData('rating_beach') == 4} checked="checked"{/if} value="4" type="radio" class="star"/>
                        <input name="{$__f->encode('feedback_extended_rating_beach')}"{if $model->getExtendedData('rating_beach') == 5 || $model->getExtendedData('rating_beach') === null} checked="checked"{/if} value="5" type="radio" class="star" />
                    </div>
                </div>

                <div class="subgroup-part clearfix">
                    <span class="sublabel">Территория</span>

                    <div class="rateBlock">
                        <input name="{$__f->encode('feedback_extended_rating_territory')}"{if $model->getExtendedData('rating_territory') == 1} checked="checked"{/if} value="1" type="radio" class="star"/>
                        <input name="{$__f->encode('feedback_extended_rating_territory')}"{if $model->getExtendedData('rating_territory') == 2} checked="checked"{/if} value="2" type="radio" class="star"/>
                        <input name="{$__f->encode('feedback_extended_rating_territory')}"{if $model->getExtendedData('rating_territory') == 3} checked="checked"{/if} value="3" type="radio" class="star"/>
                        <input name="{$__f->encode('feedback_extended_rating_territory')}"{if $model->getExtendedData('rating_territory') == 4} checked="checked"{/if} value="4" type="radio" class="star"/>
                        <input name="{$__f->encode('feedback_extended_rating_territory')}"{if $model->getExtendedData('rating_territory') == 5 || $model->getExtendedData('rating_territory') === null} checked="checked"{/if} value="5" type="radio" class="star" />
                    </div>
                </div>
            </div>

            <div class="subgroup clearfix">
                <div class="subgroup-part clearfix">
                    <span class="sublabel">Сервис</span>

                    <div class="rateBlock">
                        <input name="{$__f->encode('feedback_extended_rating_service')}"{if $model->getExtendedData('rating_service') == 1} checked="checked"{/if} value="1" type="radio" class="star"/>
                        <input name="{$__f->encode('feedback_extended_rating_service')}"{if $model->getExtendedData('rating_service') == 2} checked="checked"{/if} value="2" type="radio" class="star"/>
                        <input name="{$__f->encode('feedback_extended_rating_service')}"{if $model->getExtendedData('rating_service') == 3} checked="checked"{/if} value="3" type="radio" class="star"/>
                        <input name="{$__f->encode('feedback_extended_rating_service')}"{if $model->getExtendedData('rating_service') == 4} checked="checked"{/if} value="4" type="radio" class="star"/>
                        <input name="{$__f->encode('feedback_extended_rating_service')}"{if $model->getExtendedData('rating_service') == 5 || $model->getExtendedData('rating_service') === null} checked="checked"{/if} value="5" type="radio" class="star" />
                    </div>
                </div>

                <div class="subgroup-part clearfix">
                    <span class="sublabel">Питание</span>

                    <div class="rateBlock">
                        <input name="{$__f->encode('feedback_extended_rating_food')}"{if $model->getExtendedData('rating_food') == 1} checked="checked"{/if} value="1" type="radio" class="star"/>
                        <input name="{$__f->encode('feedback_extended_rating_food')}"{if $model->getExtendedData('rating_food') == 2} checked="checked"{/if} value="2" type="radio" class="star"/>
                        <input name="{$__f->encode('feedback_extended_rating_food')}"{if $model->getExtendedData('rating_food') == 3} checked="checked"{/if} value="3" type="radio" class="star"/>
                        <input name="{$__f->encode('feedback_extended_rating_food')}"{if $model->getExtendedData('rating_food') == 4} checked="checked"{/if} value="4" type="radio" class="star"/>
                        <input name="{$__f->encode('feedback_extended_rating_food')}"{if $model->getExtendedData('rating_food') == 5 || $model->getExtendedData('rating_food') === null} checked="checked"{/if} value="5" type="radio" class="star" />
                    </div>
                </div>

                <div class="subgroup-part clearfix">
                    <span class="sublabel">Анимация</span>

                    <div class="rateBlock">
                        <input name="{$__f->encode('feedback_extended_rating_anim')}"{if $model->getExtendedData('rating_anim') == 1} checked="checked"{/if} value="1" type="radio" class="star"/>
                        <input name="{$__f->encode('feedback_extended_rating_anim')}"{if $model->getExtendedData('rating_anim') == 2} checked="checked"{/if} value="2" type="radio" class="star"/>
                        <input name="{$__f->encode('feedback_extended_rating_anim')}"{if $model->getExtendedData('rating_anim') == 3} checked="checked"{/if} value="3" type="radio" class="star"/>
                        <input name="{$__f->encode('feedback_extended_rating_anim')}"{if $model->getExtendedData('rating_anim') == 4} checked="checked"{/if} value="4" type="radio" class="star"/>
                        <input name="{$__f->encode('feedback_extended_rating_anim')}"{if $model->getExtendedData('rating_anim') == 5 || $model->getExtendedData('rating_anim') === null} checked="checked"{/if} value="5" type="radio" class="star" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="control-group{if $__f->e('feedback_extended_agreed_rules') !== null} has-error{/if}">
        <label>
            Рекомендую для:
        </label>
        <div class="controls">
            <div class="subgroup subgroup-closer clearfix">
                <div class="subgroup-part clearfix">
                    <label for="{$__f->encode('feedback_extended_recommend_family')}">
                        <input type="checkbox" name="{$__f->encode('feedback_extended_recommend_family')}" id="{$__f->encode('feedback_extended_recommend_family')}" value="1"{if $model->getExtendedData('recommend_family')} checked="checked"{/if} />
                        Семьи
                    </label>
                </div>

                <div class="subgroup-part clearfix">
                    <label for="{$__f->encode('feedback_extended_recommend_young')}">
                        <input type="checkbox" name="{$__f->encode('feedback_extended_recommend_young')}" id="{$__f->encode('feedback_extended_recommend_young')}" value="1"{if $model->getExtendedData('recommend_young')} checked="checked"{/if} />
                        Молодежи
                    </label>
                </div>

                <div class="subgroup-part clearfix">
                    <label for="{$__f->encode('feedback_extended_recommend_family_children')}">
                        <input type="checkbox" name="{$__f->encode('feedback_extended_recommend_family_children')}" id="{$__f->encode('feedback_extended_recommend_family_children')}" value="1"{if $model->getExtendedData('recommend_family_children')} checked="checked"{/if} />
                        Семьям с детьми
                    </label>
                </div>
            </div>

            <div class="subgroup clearfix">
                <div class="subgroup-part clearfix">
                    <label for="{$__f->encode('feedback_extended_recommend_old')}">
                        <input type="checkbox" name="{$__f->encode('feedback_extended_recommend_old')}" id="{$__f->encode('feedback_extended_recommend_old')}" value="1"{if $model->getExtendedData('recommend_old')} checked="checked"{/if} />
                        Пенсионерам
                    </label>
                </div>

                <div class="subgroup-part clearfix">
                    <label for="{$__f->encode('feedback_extended_recommend_dont_ask')}">
                        <input type="checkbox" name="{$__f->encode('feedback_extended_recommend_dont_ask')}" id="{$__f->encode('feedback_extended_recommend_dont_ask')}" value="1"{if $model->getExtendedData('recommend_dont_ask')} checked="checked"{/if} />
                        Воздержусь
                    </label>
                </div>

                <div class="subgroup-part clearfix">
                    <label for="{$__f->encode('feedback_extended_recommend_no_opinion')}">
                        <input type="checkbox" name="{$__f->encode('feedback_extended_recommend_no_opinion')}" id="{$__f->encode('feedback_extended_recommend_no_opinion')}" value="1"{if $model->getExtendedData('recommend_no_opinion')} checked="checked"{/if} />
                        Не рекомендую
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="control-group{if $__f->e('feedback_extended_date_staying') !== null} has-error{/if}">
        <label for="feedback_extended_date_staying">
            Дата отдыха:
            <span class="form-asterisk">*</span>
        </label>

        <div class="controls">
            <input type="text" class="input-text has-datepicker" id="feedback_extended_date_staying" name="{$__f->encode('feedback_extended_date_staying')}" value="{$model->getExtendedData('date_staying')|escape}" />

            <div class="help-block error">
                {if $__f->e('feedback_extended_date_staying') == 'INVALID_FORMAT'}Неверный формат{else}Обязательное поле{/if}
            </div>
        </div>
    </div>

    <div class="control-group{if $__f->e('feedback_message') !== null} has-error{/if}">
        <label for="{$__f->encode('feedback_message')}">
            Текст отзыва:
            <span class="form-asterisk">*</span>
        </label>

        <div class="controls">
            <textarea name="{$__f->encode('feedback_message')}" id="{$__f->encode('feedback_message')}" class="textarea-vertical">{$model->getMessage()|escape}</textarea>

            <div class="help-block error">
                Обязательное поле
            </div>
        </div>
    </div>

    <div class="control-group{if $__f->e('feedback_extended_agreed_rules') !== null} has-error{/if}">
        <label for="{$__f->encode('feedback_extended_agreed_rules')}" class="controls">
            <input type="checkbox" name="{$__f->encode('feedback_extended_agreed_rules')}" id="{$__f->encode('feedback_extended_agreed_rules')}" value="1"{if $model->getExtendedData('agreed_rules')} checked="checked"{/if} />
            Я согласен с <a href="#modal-rules-feedback-photos" data-toggle="modal">правилами размещения отзывов и фотографий</a> на ресурсе moihottur.ru
            <span class="form-asterisk">*</span>

            <div class="help-block error">
                Обязательное поле
            </div>
        </label>
    </div>

    <div class="control-group{if $__f->e('feedback_extended_agreed_pdp') !== null} has-error{/if}">
        <label for="{$__f->encode('feedback_extended_agreed_pdp')}" class="controls">
            <input type="checkbox" name="{$__f->encode('feedback_extended_agreed_pdp')}" id="{$__f->encode('feedback_extended_agreed_pdp')}" value="1"{if $model->getExtendedData('agreed_pdp')} checked="checked"{/if} />
            Я согласен с <a href="#modal-personal-data-processing" data-toggle="modal">«Соглашением на обработку персональных данных»</a>
            <span class="form-asterisk">*</span>

            <div class="help-block error">
                Обязательное поле
            </div>
        </label>
    </div>

    <div class="control-group control-group-files{if $__f->e('photos') !== null} has-error{/if}" id="dropzone-wrapper">
        {if $model->getExtendedData('photos') !== null}
            {foreach $model->getExtendedData('photos') as $file}
                {if $file}
                    <div class="dz-preview dz-file-preview">
                        <div class="dz-details">
                            <div class="dz-filename" title="{$file}">
                                <img src="{$__url->thumb($file, 100, 100)}" alt="Загруженное изображение" />

                                <span>{$file}</span>
                            </div>
                        </div>

                        <a href="#" class="dz-remove" onclick="if (confirm('Точно удалить этот файл?')) $(this).parents('.dz-preview').remove(); return false;">Удалить</a>

                        <input type="hidden" name="{$__f->encode('feedback_extended_photos')}[]" value="{$file}" />
                    </div>
                {/if}
            {/foreach}
        {/if}

        <div class="select-files dropzone" data-href="{$__url->url('/upload/feedback-hotel')}" data-name="{$__f->encode('feedback_extended_photos')}">
            Перетащите сюда нужные файлы или просто щёлкните и выберите их вручную.
        </div>

        <div class="help-block error">
            Проблема с одним из файлов
        </div>
    </div>

    <footer class="form-footer">
        <div class="form-field">
            <div class="form-button-container">
                <div class="pull-right">
                    <div class="form-remark">
                        <span class="form-asterisk">*</span> &mdash; обязательные для заполнения поля
                    </div>
                </div>

                <input type="submit" name="{$__f->encode('__send')}" class="site-btn" value="Отправить" />
            </div>
        </div>
    </footer>
</form>