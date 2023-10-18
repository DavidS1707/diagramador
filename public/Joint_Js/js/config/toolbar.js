/*! JointJS+ v3.7.2 - HTML5 Diagramming Framework - TRIAL VERSION

Copyright (c) 2023 client IO

 2023-09-20 


This Source Code Form is subject to the terms of the JointJS+ Trial License
, v. 2.0. If a copy of the JointJS+ License was not distributed with this
file, You can obtain one at https://www.jointjs.com/license
 or from the JointJS+ archive as was distributed by client IO. See the LICENSE file.*/


var App = App || {};
App.config = App.config || {};

(function() {

    'use strict';

    App.config.toolbar = {
        groups: {
            'undo-redo': { index: 1 },
            'clear': { index: 2 },
            'export': { index: 3 },
            'print': { index: 4 },
            'fullscreen': { index: 5 },
            'order': { index: 6 },
            // 'layout': { index: 7 },
            'zoom': { index: 8 },
            'grid': { index: 9 },
            //'snapline': { index: 10 }
        },
        tools: [
            //VOLVER UN CAMBIO ATRAS
            {
                type: 'undo',
                name: 'undo',
                group: 'undo-redo',
                attrs: {
                    button: {
                        'data-tooltip': 'Undo',
                        'data-tooltip-position': 'top',
                        'data-tooltip-position-selector': '.toolbar-container'
                    }
                }
            },
            //IR UN CAMBIO ADELANTE
            {
                type: 'redo',
                name: 'redo',
                group: 'undo-redo',
                attrs: {
                    button: {
                        'data-tooltip': 'Redo',
                        'data-tooltip-position': 'top',
                        'data-tooltip-position-selector': '.toolbar-container'
                    }
                }
            },
            //BOTON DE ELIMINAR O LIMPIAR PIZARRA
            {
                type: 'button',
                name: 'clear',
                group: 'clear',
                attrs: {
                    button: {
                        id: 'btn-clear',
                        'data-tooltip': 'Clear Paper',
                        'data-tooltip-position': 'top',
                        'data-tooltip-position-selector': '.toolbar-container'
                    }
                }
            },
            //EXPORTAR SVG
            // {
            //     type: 'button',
            //     name: 'svg',
            //     group: 'export',
            //     text: 'Export SVG',
            //     attrs: {
            //         button: {
            //             id: 'btn-svg',
            //             'data-tooltip': 'Open as SVG in a pop-up',
            //             'data-tooltip-position': 'top',
            //             'data-tooltip-position-selector': '.toolbar-container'
            //         }
            //     }
            // },
            //EXPORTAR PNG
            {
                type: 'button',
                name: 'png',
                group: 'export',
                text: 'Export PNG',
                attrs: {
                    button: {
                        id: 'btn-png',
                        'data-tooltip': 'Open as PNG in a pop-up',
                        'data-tooltip-position': 'top',
                        'data-tooltip-position-selector': '.toolbar-container'
                    }
                }
            },
            //BOTON DE IMPRIMIR
            {
                type: 'button',
                name: 'print',
                group: 'print',
                attrs: {
                    button: {
                        id: 'btn-print',
                        'data-tooltip': 'Open a Print Dialog',
                        'data-tooltip-position': 'top',
                        'data-tooltip-position-selector': '.toolbar-container'
                    }
                }
            },
            //ENVIAR AL FRENTE A UN OBJETO
            {
                type: 'button',
                name: 'to-front',
                group: 'order',
                text: 'Send To Front',
                attrs: {
                    button: {
                        id: 'btn-to-front',
                        'data-tooltip': 'Bring Object to Front',
                        'data-tooltip-position': 'top',
                        'data-tooltip-position-selector': '.toolbar-container'
                    }
                }
            },
            //ENVIAR ATRAS A UN OBJETO
            {
                type: 'button',
                name: 'to-back',
                group: 'order',
                text: 'Send To Back',
                attrs: {
                    button: {
                        id: 'btn-to-back',
                        'data-tooltip': 'Send Object to Back',
                        'data-tooltip-position': 'top',
                        'data-tooltip-position-selector': '.toolbar-container'
                    }
                }
            },
            //AUTOLAYOUT XD
            // {
            //     type: 'button',
            //     group: 'layout',
            //     name: 'layout',
            //     attrs: {
            //         button: {
            //             id: 'btn-layout',
            //             'data-tooltip': 'Auto-layout Graph',
            //             'data-tooltip-position': 'top',
            //             'data-tooltip-position-selector': '.toolbar-container'
            //         }
            //     }
            // },
            //ZOOM HASTA QUE SE VEA COMPLETO
            {
                type: 'zoom-to-fit',
                name: 'zoom-to-fit',
                group: 'zoom',
                attrs: {
                    button: {
                        'data-tooltip': 'Zoom To Fit',
                        'data-tooltip-position': 'top',
                        'data-tooltip-position-selector': '.toolbar-container'
                    }
                }
            },
            //ZOOM AL DIAGRAMA
            {
                type: 'zoom-out',
                name: 'zoom-out',
                group: 'zoom',
                attrs: {
                    button: {
                        'data-tooltip': 'Zoom Out',
                        'data-tooltip-position': 'top',
                        'data-tooltip-position-selector': '.toolbar-container'
                    }
                }
            },
            {
                type: 'label',
                name: 'zoom-slider-label',
                group: 'zoom',
                text: 'Zoom:'
            },
            {
                type: 'zoom-slider',
                name: 'zoom-slider',
                group: 'zoom'
            },
            {
                type: 'zoom-in',
                name: 'zoom-in',
                group: 'zoom',
                attrs: {
                    button: {
                        'data-tooltip': 'Zoom In',
                        'data-tooltip-position': 'top',
                        'data-tooltip-position-selector': '.toolbar-container'
                    }
                }
            },
            //separador
            {
                type: 'separator',
                group: 'grid'
            },
            //TAMAÑO DE PIZARRA
            {
                type: 'label',
                name: 'grid-size-label',
                group: 'grid',
                text: 'Grid size:',
                attrs: {
                    label: {
                        'data-tooltip': 'Change Grid Size',
                        'data-tooltip-position': 'top',
                        'data-tooltip-position-selector': '.toolbar-container'
                    }
                }
            },
            {
                type: 'range',
                name: 'grid-size',
                group: 'grid',
                text: 'Grid size:',
                min: 1,
                max: 50,
                step: 1,
                value: 10
            },
            //separador
            {
                type: 'separator',
                group: 'snapline'
            },
            //SNAPLINE [check]
            // {
            //     type: 'checkbox',
            //     name: 'snapline',
            //     group: 'snapline',
            //     label: 'Snaplines:',
            //     value: true,
            //     attrs: {
            //         input: {
            //             id: 'snapline-switch'
            //         },
            //         label: {
            //             'data-tooltip': 'Enable/Disable Snaplines',
            //             'data-tooltip-position': 'top',
            //             'data-tooltip-position-selector': '.toolbar-container'
            //         }
            //     }
            // },
            //FULLSCREEN O PANTALLA GRANDE
            {
                type: 'fullscreen',
                name: 'fullscreen',
                group: 'fullscreen',
                attrs: {
                    button: {
                        'data-tooltip': 'Toggle Fullscreen Mode',
                        'data-tooltip-position': 'top',
                        'data-tooltip-position-selector': '.toolbar-container'
                    }
                }
            }
        ]
    };
})();
