window.CKEDITOR = CKEDITOR;

CKEDITOR.plugins.add( 'pgrfilemanager' );

CKEDITOR.config.filebrowserBrowseUrl = CKEDITOR.basePath+'plugins/pgrfilemanager/PGRFileManager.php',
CKEDITOR.config.filebrowserImageBrowseUrl = CKEDITOR.basePath+'plugins/pgrfilemanager/PGRFileManager.php?type=Image',
CKEDITOR.config.filebrowserFlashBrowseUrl = CKEDITOR.basePath+'plugins/pgrfilemanager/PGRFileManager.php?type=Flash',
CKEDITOR.config.filebrowserUploadUrl = CKEDITOR.basePath+'plugins/pgrfilemanager/PGRFileManager.php?type=Files',
CKEDITOR.config.filebrowserImageUploadUrl = CKEDITOR.basePath+'plugins/pgrfilemanager/PGRFileManager.php?type=Image',
CKEDITOR.config.filebrowserFlashUploadUrl = CKEDITOR.basePath+'plugins/pgrfilemanager/PGRFileManager.php?type=Flash'
