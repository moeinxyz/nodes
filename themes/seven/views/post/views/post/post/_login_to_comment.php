<li><?php use app\modules\post\Module;?>
    <img src="http://www.placehold.it/60x60/EFEEEF/AAAAAA&text=You">
    <div>
        <div class="row">
            <div class="form-group">
                <div class="controls">
                    <div class="col-md-12 controls">
                        <div class="form-group field-comment-text required">
                            <textarea id="comment-text" class="form-control" disabled="true">
                            </textarea>
                        </div>                
                    </div>
                </div>
            </div>
            <div class="form-group">
                <div class="controls">
                    <div class="col-md-12 controls">
                        <button type="button" class="btn btn-primary btn-block" name="comment-button" data-toggle="modal" data-target="#loginmodal"><?= Module::t('post','comment.loginToComment');?></button>
                    </div>
                </div>            
            </div>
        </div>                    
    </div>        
</li>                
