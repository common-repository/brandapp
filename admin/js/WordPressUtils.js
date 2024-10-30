window.BrandappWordPressUtils = (function() {
    var __$ = null;
    
    var dataURLToBlob = function(dataURL) {
        // console.log('dataURLToBlob called');
        
        var byteString;
        if (dataURL.split(',')[0].indexOf('base64') >= 0) {
            byteString = atob(dataURL.split(',')[1]);
        } 
        else {
            byteString = unescape(dataURL.split(',')[1]);
        }
        
        var mimeString = dataURL.split(',')[0].split(':')[1].split(';')[0],
            ab = new ArrayBuffer(byteString.length),
            ia = new Uint8Array(ab);
        for (var i = 0; i < byteString.length; i++) {
            ia[i] = byteString.charCodeAt(i);
        }
        
        try {
            return new Blob([ab], {type: mimeString});
        } 
        catch (e) {
            var BlobBuilder = window.WebKitBlobBuilder || window.MozBlobBuilder;
            var bb = new BlobBuilder();
            bb.append(ab);
            return bb.getBlob(mimeString);
        }
    }
    
    var uploadBlob = function(blob, filename) {
        // console.log('uploadBlob', filename);
        
        var item = blob;
        if (window.File) {
            item = new File([blob], filename);
        }
        
        var uploaders = window.wp.media.frame.views._views['.media-frame-uploader'];
        
        // console.log('uploaders', uploaders);
        
        if (uploaders === undefined) {
            return false;
        }
        var view = uploaders[0];
        // console.log('view', view);
        // console.log('item', item);
        
        view.uploader.uploader.addFile(item);
        
    }
    
    var uploadURL = function(url, filename) {
        var image = new Image();
        image.onload = function() {
            var canvas = document.createElement('canvas');
            canvas.width = this.naturalWidth;
            canvas.height = this.naturalHeight;
            canvas.getContext('2d').drawImage(this, 0, 0);
            
            var dataURL;
            
            if (filename.substr(-4) === '.jpg') {
                dataURL = canvas.toDataURL('image/jpeg', .75);
            }
            else {
                dataURL = canvas.toDataURL('image/png');
            }
            
            var blob = dataURLToBlob(dataURL);
            
            // console.log('blob');
            // console.log(blob);
            
            uploadBlob(blob, filename);
        };
        image.crossOrigin = 'anonymous';
        image.src = url;
        
        return;
    }
    
    
    
    
    
    
    var __addTabIfNotExists = function() {
        // console.log('__addTabIfNotExists!');
        // console.log( 'length',  __$('button#brandapp-tab-button.media-menu-item').length );
        
        __$( 'button#brandapp-tab-button.media-menu-item').remove();
        __$( '#brandapp-content-wrapper').remove();
        // console.log( 'removing...');
        
        
        if ( __$('#brandapp-tab-button').length === 0) {
            // console.log('NOT THERE will continue');
        }
        else {
            // console.log('THERE will return');
            return false;
        }
        
        __$( "div.media-frame-router .media-router" ).append('\
            <button id="brandapp-tab-button" type="button" role="tab" class="media-menu-item" aria-selected="true" tabindex="-2">\
                BrandApp\
            </button>\
        ');
        
        __$( "div.media-frame-router .media-router .media-menu-item" ).click(function( event ) {
            var $id = event.target.id;
            // console.log('$id', $id);
            
            __$( "div.media-frame-router .media-router .media-menu-item" ).removeClass('active');
            __$( this ).addClass('active');
            
            if ($id == 'brandapp-tab-button') {
                if ( __$(this).closest('.media-modal').find( '#brandapp-content-wrapper').length === 0) {
                    // console.log('not found. adding iframe');
                    
                    var src;
                    
                    if (window.location.host == 'localhost') {
                        src = 'https://localhost:8080/';
                    }
                    else {
                        src = 'https://app.brandapp.io/';
                    }
                    
                    var embedParams = {
                        parentApp: 'wordpress', //default: null
                        btnText: 'Place in Wordpress', // default: 'Insert'
                        btnBackground: '#444444', // default: '#444444'
                        btnColor: '#ffffff', // default: '#ffffff'
                        navigationOpen: false, // false
                    };
                    
                    src += '?embedParams=' + encodeURIComponent(JSON.stringify(embedParams));
                    // console.log('src',src);
                    
                    __$(this).closest('.media-modal').find( '.media-frame-content')
                        .append('\
                            <div id="brandapp-content-wrapper" style="background: #fff; position: absolute; top: 0; left: 0; right: 0; bottom: 0; overflow: hidden; z-index: 10000;">\
                                <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">\
                                    <span>Loading BrandApp...</span>\
                                </div>\
                                <iframe allowtransparency="true" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; width: 100%; height: 100%;" src="'+src+'"></iframe>\
                            </div>\
                        ')
                        .css({
                            bottom: 0,
                        });
                }
                else {
                    // console.log('iframe already there...showing');
                    __$(this).closest('.media-modal').find( '#brandapp-content-wrapper').show();
                }
            }
            else {
                __$(this).closest('.media-modal').find( '#brandapp-content-wrapper').hide();
            }
        });
        
        return true;
    }
    
    
    
    // Public
    return {
        init: function($) {
            __$ = $;
            
            // is wp.media defined?
            if ( window.wp.media ) {
                // console.log('window.wp.media set...');
                
                try {
                    window.wp.media.frame.on('all', function(eventName) {
                        if (eventName == 'open') {
                            __addTabIfNotExists();
                            // console.log('media modal featured image OPEN');
                        }
                        else if (eventName == 'close') {
                            // console.log('media modal featured image close');
                        }
                    });
                } 
                catch (e) {
                    console.log('Could find wp.media on hook');
                }
                
                window.wp.media.view.Modal.prototype.on( 'all', function(eventName) {
                    if (eventName == 'open') {
                        __addTabIfNotExists();
                        // console.log('media modal regular image open');
                    }
                    else if (eventName == 'close') {
                        // console.log('media modal regular image close');
                    }
                });
            }
            else {
                // console.log('window.wp.media NOT  set...');
            }
            
            // __$(window).resize(function() {
            //     console.log('detected resize...');
            //     // __positionIFrame();
            // });
            
            window.addEventListener('message', function(event) {
                // console.log('message event', event);
                
                if (event) {
                    var url = event.data.media.url;
                    var extension = event.data.media.extension;
                    // var filename = event.data.name +'(meta:'+event.data.accountId+':'+event.data.pageId +'  .'+ extension;
                    var payLoadSentDateTime = new Date( event.data.payload.sentAt ).toUTCString();
                    var filename = event.data.name +' ('+ payLoadSentDateTime +').'+ extension;
                    
                    uploadURL(url, filename);
                    
                    
                    
                    // if menu items are links or buttons 
                    var text = window.wp.media.view.l10n.mediaLibraryTitle;
                    
                    var selector = 'a.media-menu-item:contains("' + (text) + '"):visible';
                    var $elements = __$(selector);
                    var length = $elements.length;
                    
                    var $element;
                    
                    if (length > 0) {
                        $element = $elements;
                    }
                    else {
                        selector = 'button.media-menu-item:contains("' + (text) + '"):visible';
                        $element = __$(selector);
                    }
                    
                    $element.trigger('click');
                    
                }
                
            });
            
            return true;
        },

    };
})();
