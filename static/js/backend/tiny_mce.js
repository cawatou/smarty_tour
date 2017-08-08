function DxFileBrowser(field_name, url, type, win) 
{
    if (tinyMCE.selectedInstance.fileBrowserAlreadyOpen) {
        return false;
    }

    window.custom_tiny = {'callback_field': field_name};

    tinyMCE.activeEditor.windowManager.open({
        file : TINY_FILEMANAGER_PATH,
        title : 'DxFile Browser',
        width : 1000,  
        height : 500,
        close_previous : "no",
        popup_css : false
    }, {
        window : win,
        input : field_name
    });
    return false;
}

function initRedactor(_element, _width, _height, _lang)
{
    var 
		_height = _height || 400,
		_width  = _width || '100%',
		_lang   = _lang || 'ru';
    tinyMCE.init({
            // General options
            mode : "exact",
            elements: _element,
            theme : "advanced",
            width : _width,
            height : _height,        
            language : _lang,
			/*
            relative_urls : true,
            remove_script_host : true,
			document_base_url : TINY_BASE_URL,
			*/
			convert_urls : false,
            file_browser_callback : "DxFileBrowser",

            //plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
            plugins: "paste,table,fullscreen,visualchars,typograf,template",
            // Theme options
            theme_advanced_buttons1 : "newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,formatselect,|,template,|,typograf",
            theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,bullist,numlist,|,outdent,indent,|,undo,redo,|,link,unlink,image,cleanup,code,",
            theme_advanced_buttons3 : "tablecontrols,|,sub,sup,|,charmap,visualchars,fullscreen",
            theme_advanced_toolbar_location : "top",
            theme_advanced_toolbar_align : "left",
            theme_advanced_statusbar_location : "bottom",
            theme_advanced_resizing : false,
            theme_advanced_blockformats: "p,h1,h2,h3",
            // templates
            template_external_list_url: "/static/js/backend/tiny_mce/templates/templates.js",
            content_css: "/static/js/backend/tiny_mce/templates/templates.css"

            // Example content CSS (should be your site CSS)
            //content_css : TINY_STYLE_PATH
    });    
}

function initLightRedactor(_element, _width, _height, _lang)
{
    var 
		_height = _height || 150,
		_width  = _width || '100%',
		_lang   = _lang || 'ru';

    tinyMCE.init({
            // General options
            mode : "exact",
            elements: _element,
            theme : "advanced",
            width : _width,
            height : _height,  
            language: _lang,
			/*
            relative_urls : true,
            remove_script_host : true,
			document_base_url : TINY_BASE_URL,
			*/
			convert_urls : false,
			
            //plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
            plugins: "paste,table,fullscreen,visualchars,typograf",
            // Theme options
            theme_advanced_buttons1 : "newdocument,|,bold,italic,underline,strikethrough,|,cut,copy,paste,pastetext,pasteword,|,bullist,numlist,|,undo,redo,|,link,unlink,cleanup,code,|,sub,sup,|,charmap,visualchars,fullscreen,|,typograf",
            theme_advanced_buttons2 : "",
            theme_advanced_buttons3 : "",
            theme_advanced_toolbar_location : "top",
            theme_advanced_toolbar_align : "left",
            theme_advanced_statusbar_location : "bottom",
            theme_advanced_resizing : false,
            theme_advanced_blockformats: "p,h1,h2,h3"

            // Example content CSS (should be your site CSS)
            // content_css : TINY_STYLE_PATH
    });    
}