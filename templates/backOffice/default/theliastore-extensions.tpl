{extends file="admin-layout.tpl"}
{$info_category = []}
{$info_sub_category = []}
{$info_picto = []}


{block name="after-admin-css"}
{/block}

{block name="main-content"}
{$info_picto = []}
{$info_sub_category = []}

{loop type="theliastore_extensioncategoryloop" name="extensioncategoryloop" parent="1"}

    {$info_sub_category = []}

    {loop type="theliastore_extensioncategoryloop" name="subextensioncategoryloop" parent=$ID }
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
                    <a href="{url path="/admin/store-account/login"}" type="button" class="btn btn-primary">{intl l='I login'}</a>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <div class="modal fade" id="myAjaxModal" tabindex="-1" role="dialog" aria-labelledby="myAjaxModal" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    {*
    <div class="modal fade" id="delete_module_dialog" tabindex="-1" role="dialog" aria-labelledby="delete_module_dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">{intl d='theliastore.bo.default' l='Remove extension'}</h4>
                </div>

                <form action="{url path="/admin/store-extensions/delete"}" method="post">
                    <input type="hidden" name="id" id="delete-form-id" value="" />
                    <div class="modal-body">
                        <div class="checkbox">
                            <label>
                            <input type="checkbox" name="confirm" value="1" required> Je confirme la suppression de l'extension
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">{intl l='Cancel'}</button>
                        <button type="submit" class="btn btn-primary">{intl l='Delete'}</button>
                    </div>
                </form>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    *}

{* Delete module confirmation dialog *}

{capture "delete_module_dialog"}
    <input type="hidden" name="module_id" id="delete_module_id" value="" />

    <div class="row">
        <div class="col-md-12">
		    <div class="form-group">
		        <div class="checkbox">
		            <label>
		                <input type="checkbox" name="delete-module-data" value="1">
		                {intl l="Delete also module data"}
		            </label>
		        </div>
		    </div>
		</div>
	</div>

{/capture}

{include
    file = "includes/generic-confirm-dialog.html"

    dialog_id       = "delete_module_dialog"
    dialog_title    = {intl l="Delete a module"}
    dialog_message  = {intl l="Do you really want to delete this module ?"}

    form_action         = {url path='/admin/store-extensions/delete'}
    form_content        = {$smarty.capture.delete_module_dialog nofilter}
}

{/block}

{block name="javascript-initialization"}
    <script>
    $('.my-ajax-action').on('click',function(){
        var myUrl = $(this).attr('href');
        $('body').append('<div id="myloader"><div class="loading" ></div></div>');
		$.ajax({
			type: "POST",
			url: myUrl,
			dataType: "html",
			success: function(msg){
                $('#myloader').remove();
				$('#myAjaxModal .modal-content').html(msg);
				$('#myAjaxModal').modal('show');
			}
		});
        return false;
    });
    /*
    $('.module-delete-action').on('click', function () {
        $('#delete-form-id').val($(this).data('id'));
        $('#delete_module_dialog').modal('show');
        return false;
    });
    */
    $(".module-delete-action").click(function(){
        $("#delete_module_id").val($(this).data("id"));
    });
    </script>
{/block}