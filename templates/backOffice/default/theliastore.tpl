{extends file="admin-layout.tpl"}
{$info_category = []}
{$info_sub_category = []}
{$info_picto = []}


{block name="after-admin-css"}
{/block}

{block name="main-content"}
{$info_picto = []}
{$info_sub_category = []}
{loop type="extensioncategoryloop" name="extensioncategoryloop" parent="1"}

    {$info_sub_category = []}

    {loop type="extensioncategoryloop" name="subextensioncategoryloop" parent=$ID }
        {$info_sub_category[$ID] =
            ['titre' => $TITLE,
            'picto' => $POSTSCRIPTUM]
        }
        {$info_picto[$ID] = $POSTSCRIPTUM}
    {/loop}

    {$info_category[$ID] =
        [
        'titre' => $TITLE,
        'picto' => $POSTSCRIPTUM,
        'subcategory' => $info_sub_category
        ]
    }
    {$info_picto[$ID] = $POSTSCRIPTUM}
{/loop}
{/block}

{block name="before-javascript-include"}

    <div class="modal fade" tabindex="-1" role="dialog" id="modalcreateaccount">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">{intl d='theliastore.bo.default' l='Login'}</h4>
                </div>
                <div class="modal-body">
                    {intl d='theliastore.bo.default' l='TextModalLogin %url' url="{url path='/admin/store-account/create'}"}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">{intl l='Cancel'}</button>
                    <a href="{url path="/admin/store-account/login"}" type="button" class="btn btn-primary">{intl d='theliastore.bo.default' l='I login'}</a>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

{/block}

{block name="javascript-initialization"}
    <script>
        {literal}
        var menutohide;

        $('.ts-submenu-collapse').on('show.bs.collapse', function () {
            //$('.ts-submenu-collapse.in').collapse('hide');
            menutohide = $('.ts-submenu-collapse.in');
        });
        $('.ts-submenu-collapse').on('shown.bs.collapse', function () {
            //$('.ts-submenu-collapse.in').collapse('hide');
            menutohide.collapse('hide');
        });

        var newsativeindicator = $('#carousel-news .ativeindicator');
        var newsativeindicator_width = $('#carousel-news .carousel-indicators li.active').width();
        newsativeindicator.css({
            left:0,
            bottom:0,
            height:"5px",
            width:newsativeindicator_width
        });
        $('#carousel-news').on('slide.bs.carousel', function (e) {

            var nextLi = $('#carousel-news .carousel-indicators li')[$(e.relatedTarget).data('numtab')];

            var offsetLeft = 0;
            if(typeof nextLi != 'undefined'){
                offsetLeft = nextLi.offsetLeft;
            }

            newsativeindicator.animate({left:offsetLeft+"px"},250);

        });
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        });
        {/literal}
    </script>
{/block}