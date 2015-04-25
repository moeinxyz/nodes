<div class="row">
    <div class="col-md-10 col-md-offset-1 col-xs-12">
        <div class="top-buffer"></div>
        <div class="box">
            <div class="box-body table-responsive no-padding medium-font-size">
                <?= $this->render('_admin',[
                            'dataProvider'  =>  $dataProvider,
                            'postId'        =>  $postId
                ]);?>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div>
</div>