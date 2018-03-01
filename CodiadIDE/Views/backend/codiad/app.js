Ext.define('Shopware.apps.Codiad', {
    extend:'Enlight.app.SubApplication',
    name:'Shopware.apps.Codiad',
    bulkLoad: true,
    loadPath:'{url action=load}',

    controllers:[],
    models:[],
    stores:[],
    views:[

    ],

    launch: function() {

        Ext.Ajax.request({
            url: '{url controller=Codiad action=getUrl}',
            params: {
                
            },
            async: false,
            success: function(responseData, request) {
                var dataURL = responseData.responseText;

                return new Ext.Window({
                    title : 'Codiad IDE',
                    width : 1200,
                    height: 600,
                    minimizable: true,
                    maximizable: true,
                    layout : 'fit',
                    listeners: {
                        "minimize": function (window, opts) {
                            window.collapse();
                            window.setWidth(150);
                            window.alignTo(Ext.getBody(), 'bl-bl')
                        }
                    },
                    tools: [{
                        type: 'restore',
                        handler: function (evt, toolEl, owner, tool) {
                            var window = owner.up('window');
                            window.setWidth(1200);
                            window.expand('', false);
                            window.center();
                        }
                    }],
                    items : [{
                        xtype : "component",
                        id    : 'iframe-win',
                        autoEl : {
                            tag : "iframe",
                            src : dataURL
                        }
                    }]
                }).show();
            }
        });
    }
});