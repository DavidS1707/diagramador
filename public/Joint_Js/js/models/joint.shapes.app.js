/*! JointJS+ v3.7.2 - HTML5 Diagramming Framework - TRIAL VERSION

Copyright (c) 2023 client IO

 2023-09-20 


This Source Code Form is subject to the terms of the JointJS+ Trial License
, v. 2.0. If a copy of the JointJS+ License was not distributed with this
file, You can obtain one at https://www.jointjs.com/license
 or from the JointJS+ archive as was distributed by client IO. See the LICENSE file.*/


const cache = new Map();

(function(joint) {

    'use strict';

    var dia = joint.dia;

    joint.shapes.custom = {}; // Define un nuevo espacio de nombres personalizado para tus formas.

    joint.shapes.custom.Lifeline = joint.shapes.basic.Generic.extend({
        markup: '<g class="rotatable"><g class="scalable"><line/></g></g>',
        defaults: joint.util.deepSupplement({
            type: 'custom.Lifeline',
            size: { width: 10, height: 100 }, // Ancho mínimo y altura inicial de la línea de vida
        }, joint.shapes.basic.Generic.prototype.defaults),
    });

    // Luego, define un nuevo enlace de la línea de vida:
    joint.shapes.custom.LifelineLink = joint.dia.Link.extend({
        defaults: {
            type: 'custom.LifelineLink',
            source: { selector: 'line' },
            target: { selector: 'line' },
            router: { name: 'manhattan' },
            connector: { name: 'rounded' },
            attrs: {
                '.connection': { stroke: 'blue' },
            },
        },
    });


    joint.shapes.standard.Rectangle.define('sd.RoleGroup', {
        z: 1,
        attrs: {
            body: {
                stroke: '#DDDDDD',
                strokeWidth: 1,
                fill: '#F9FBFA'
            },
            label: {
                refY: null,
                refX: null,
                y: 'calc(h+2)',
                x: 'calc(w/2)',
                textAnchor: 'middle',
                textVerticalAnchor: 'top',
                fontSize: 12,
                fontFamily: 'sans-serif',
                textWrap: {
                    width: -10
                }
            }
        }
    }, {
        placeholder: 'What\'s the group\'s name?',

        fitRoles: function() {
            this.fitToChildren({ padding: 10 });
        }
    });

    joint.shapes.standard.Rectangle.define('sd.Role', {
        z: 2,
        size: { width: 100, height: 80 },
        attrs: {
            body: {
                stroke: '#A0A0A0',
                strokeWidth: 1,
                rx: 2,
                ry: 2
            },
            label: {
                fontSize: 18,
                fontFamily: 'sans-serif',
                textWrap: {
                    width: -10
                }
            }
        }
    }, {
        placeholder: 'What\'s the role?',

        setName: function(name) {
            this.attr(['label', 'text'], name);
        }
    });

    joint.shapes.standard.Link.define('sd.Lifeline', {
        z: 3,
        attrs: {
            line: {
                stroke: '#A0A0A0',
                strokeWidth: 1,
                targetMarker: null
            }
        }
    }, {
        attachToRole: function (role, maxY) {
            const roleCenter = role.getBBox().center();
            this.set({
                source: { id: role.id },
                target: { x: roleCenter.x, y: maxY }
            });
            role.embed(this);
        }
    });

    dia.Link.define('sd.LifeSpan', {
        z: 4,
        attrs: {
            line: {
                connection: true,
                stroke: '#222222',
                strokeWidth: 2
            },
            wrapper: {
                connection: true
            },
            icon: {
                atConnectionRatioIgnoreGradient: 0.5
            }
        }
    }, {
        markup: [{
            tagName: 'path',
            selector: 'line',
            attributes: {
                'fill': 'none',
                'pointer-events': 'none'
            }
        }, {
            tagName: 'path',
            selector: 'wrapper',
            attributes: {
                'fill': 'none',
                'stroke': 'transparent',
                'stroke-width': 20
            }
        }, {
            tagName: 'g',
            selector: 'icon',
            children: [{
                tagName: 'circle',
                attributes: {
                    'r': 12,
                    'fill': '#222222'
                }
            }, {
                tagName: 'path',
                attributes: {
                    'd': 'M 0 0 0 20', // Modifica esto para crear una línea vertical hacia abajo
                    'stroke': '#FFFFFF',
                    'stroke-width': 2,
                    'fill': 'none'
                }
            }]
        }],
        attachToMessages: function (from, to) {
            this.source(from, { anchor: { name: 'connectionRatio', args: { ratio: 1 } } });
            this.target(to, { anchor: { name: 'connectionRatio', args: { ratio: 0 } }});
        }
        
    });
    

    joint.shapes.standard.Link.define('sd.Message', {
        z: 5,
        source: { anchor: { name: 'connectionLength' }},
        target: { anchor: { name: 'connectionPerpendicular' }},
        attrs: {
            line: {
                stroke: '#4666E5',
                sourceMarker: {
                    'type': 'path',
                    'd': 'M -3 -3 -3 3 3 3 3 -3 z',
                    'stroke-width': 3
                }
            },
            wrapper: {
                strokeWidth: 20,
                cursor: 'grab'
            },
        }
    }, {
        placeholder: 'What\'s the message?',

        defaultLabel: {
            markup: [{
                tagName: 'rect',
                selector: 'labelBody'
            }, {
                tagName: 'text',
                selector: 'labelText'
            }],
            attrs: {
                labelBody: {
                    ref: 'labelText',
                    width: 'calc(w + 20)',
                    height: 'calc(h + 10)',
                    x: 'calc(x - 10)',
                    y: 'calc(y - 5)',
                    rx: 2,
                    ry: 2,
                    fill: '#4666E5'
                },
                labelText: {
                    fill: '#FFFFFF',
                    fontSize: 12,
                    fontFamily: 'sans-serif',
                    textAnchor: 'middle',
                    textVerticalAnchor: 'middle',
                    cursor: 'grab'
                }
            }
        },
        setStart: function(y) {
            this.prop(['source', 'anchor', 'args', 'length'], y);
        },
        setFromTo: function(from, to) {
            this.prop({
                source: { id: from.id },
                target: { id: to.id }
            });
        },
        setDescription: function(description) {
            this.labels([{ attrs: { labelText: { text: description }}}]);
        }
    });

    joint.shapes.standard.Actor = joint.shapes.standard.Rectangle.extend({
        markup: [
            {
                tagName: 'rect',
                selector: 'body',
                attributes: {
                    width: 100,
                    height: 40,
                    fill: 'white',
                    stroke: 'black'
                }
            },
            {
                tagName: 'text',
                selector: 'label',
                attributes: {
                    fill: 'black',
                    'font-size': 14,
                    ref: 'body',
                    'ref-x': 0.5,
                    'ref-y': 0.5,
                    'y-alignment': 'middle',
                    'x-alignment': 'middle',
                }
            }
        ],
        defaults: joint.util.defaultsDeep({
            type: 'standard.Actor',
            size: { width: 100, height: 40 },
            attrs: {
                'rect': { fill: 'white', stroke: 'black' },
                'text': { text: 'Actor', 'font-size': 14 }
            }
        }, joint.shapes.standard.Rectangle.prototype.defaults)
    });

    joint.shapes.standard.Entity = joint.shapes.standard.Ellipse.extend({
        markup: [
            {
                tagName: 'ellipse',
                selector: 'body',
                attributes: {
                    rx: 10,
                    ry: 50,
                    fill: 'white',
                    stroke: 'black'
                }
            },
            {
                tagName: 'text',
                selector: 'label',
                attributes: {
                    fill: 'black',
                    'font-size': 14,
                    ref: 'body',
                    'ref-x': 0.5,
                    'ref-y': 0.5,
                    'y-alignment': 'middle',
                    'x-alignment': 'middle',
                }
            }
        ],
        defaults: joint.util.defaultsDeep({
            type: 'standard.Entity',
            size: { width: 20, height: 100 },
            attrs: {
                'ellipse': { fill: 'white', stroke: 'black' },
                'text': { text: 'Entity', 'font-size': 14 }
            }
        }, joint.shapes.standard.Ellipse.prototype.defaults)
    });

    joint.shapes.standard.Boundary = joint.shapes.standard.Ellipse.extend({
        markup: [
            {
                tagName: 'ellipse',
                selector: 'body',
                attributes: {
                    rx: 10,
                    ry: 50,
                    fill: 'white',
                    stroke: 'black'
                }
            },
            {
                tagName: 'text',
                selector: 'label',
                attributes: {
                    fill: 'black',
                    'font-size': 14,
                    ref: 'body',
                    'ref-x': 0.5,
                    'ref-y': 0.5,
                    'y-alignment': 'middle',
                    'x-alignment': 'middle',
                }
            }
        ],
        defaults: joint.util.defaultsDeep({
            type: 'standard.Boundary',
            size: { width: 20, height: 100 },
            attrs: {
                'ellipse': { fill: 'white', stroke: 'black' },
                'text': { text: 'Boundary', 'font-size': 14 }
            }
        }, joint.shapes.standard.Ellipse.prototype.defaults)
    });

    joint.shapes.standard.Control = joint.shapes.standard.Ellipse.extend({
        markup: [
            {
                tagName: 'ellipse',
                selector: 'body',
                attributes: {
                    rx: 10,
                    ry: 50,
                    fill: 'white',
                    stroke: 'black'
                }
            },
            {
                tagName: 'text',
                selector: 'label',
                attributes: {
                    fill: 'black',
                    'font-size': 14,
                    ref: 'body',
                    'ref-x': 0.5,
                    'ref-y': 0.5,
                    'y-alignment': 'middle',
                    'x-alignment': 'middle',
                }
            }
        ],
        defaults: joint.util.defaultsDeep({
            type: 'standard.Control',
            size: { width: 20, height: 100 },
            attrs: {
                'ellipse': { fill: 'white', stroke: 'black' },
                'text': { text: 'Control', 'font-size': 14 }
            }
        }, joint.shapes.standard.Ellipse.prototype.defaults)
    });

    joint.shapes.standard.Object = joint.shapes.standard.Rectangle.extend({
        markup: [
            {
                tagName: 'rect',
                selector: 'body',
                attributes: {
                    width: 100,
                    height: 40,
                    fill: 'white',
                    stroke: 'black'
                }
            },
            {
                tagName: 'text',
                selector: 'label',
                attributes: {
                    fill: 'black',
                    'font-size': 14,
                    ref: 'body',
                    'ref-x': 0.5,
                    'ref-y': 0.5,
                    'y-alignment': 'middle',
                    'x-alignment': 'middle',
                }
            }
        ],
        defaults: joint.util.defaultsDeep({
            type: 'standard.Object',
            size: { width: 100, height: 40 },
            attrs: {
                'rect': { fill: 'white', stroke: 'black' },
                'text': { text: 'Object', 'font-size': 14 }
            }
        }, joint.shapes.standard.Rectangle.prototype.defaults)
    });

    joint.shapes.standard.Lifeline = joint.shapes.standard.Ellipse.extend({
        markup: [
            {
                tagName: 'ellipse',
                selector: 'body',
                attributes: {
                    rx: 10,
                    ry: 50,
                    fill: 'white',
                    stroke: 'black'
                }
            },
            {
                tagName: 'text',
                selector: 'label',
                attributes: {
                    fill: 'black',
                    'font-size': 14,
                    ref: 'body',
                    'ref-x': 0.5,
                    'ref-y': 0.5,
                    'y-alignment': 'middle',
                    'x-alignment': 'middle',
                }
            }
        ],
        defaults: joint.util.defaultsDeep({
            type: 'standard.Lifeline',
            size: { width: 20, height: 100 },
            attrs: {
                'ellipse': { fill: 'white', stroke: 'black' },
                'text': { text: 'Lifeline', 'font-size': 14 }
            }
        }, joint.shapes.standard.Ellipse.prototype.defaults)
    });



    //

    joint.shapes.standard.Ellipse.define('app.CircularModel', {
        attrs: {
            root: {
                magnet: false
            }
        },
        ports: {
            groups: {
                'in': {
                    markup: [{
                        tagName: 'circle',
                        selector: 'portBody',
                        attributes: {
                            'r': 10
                        }
                    }],
                    attrs: {
                        portBody: {
                            magnet: true,
                            fill: '#61549c',
                            strokeWidth: 0
                        },
                        portLabel: {
                            fontSize: 11,
                            fill: '#61549c',
                            fontWeight: 800
                        }
                    },
                    position: {
                        name: 'ellipse',
                        args: {
                            startAngle: 0,
                            step: 30
                        }
                    },
                    label: {
                        position: {
                            name: 'radial',
                            args: null
                        }
                    }
                },
                'out': {
                    markup: [{
                        tagName: 'circle',
                        selector: 'portBody',
                        attributes: {
                            'r': 10
                        }
                    }],
                    attrs: {
                        portBody: {
                            magnet: true,
                            fill: '#61549c',
                            strokeWidth: 0
                        },
                        portLabel: {
                            fontSize: 11,
                            fill: '#61549c',
                            fontWeight: 800
                        }
                    },
                    position: {
                        name: 'ellipse',
                        args: {
                            startAngle: 180,
                            step: 30
                        }
                    },
                    label: {
                        position: {
                            name: 'radial',
                            args: null
                        }
                    }
                }
            }
        }
    }, {
        portLabelMarkup: [{
            tagName: 'text',
            selector: 'portLabel'
        }]
    });

    joint.shapes.standard.Rectangle.define('app.RectangularModel', {
        attrs: {
            root: {
                magnet: false
            }
        },
        ports: {
            groups: {
                'in': {
                    markup: [{
                        tagName: 'circle',
                        selector: 'portBody',
                        attributes: {
                            'r': 10
                        }
                    }],
                    attrs: {
                        portBody: {
                            magnet: true,
                            fill: '#61549c',
                            strokeWidth: 0
                        },
                        portLabel: {
                            fontSize: 11,
                            fill: '#61549c',
                            fontWeight: 800
                        }
                    },
                    position: {
                        name: 'left'
                    },
                    label: {
                        position: {
                            name: 'left',
                            args: {
                                y: 0
                            }
                        }
                    }
                },
                'out': {
                    markup: [{
                        tagName: 'circle',
                        selector: 'portBody',
                        attributes: {
                            'r': 10
                        }
                    }],
                    position: {
                        name: 'right'
                    },
                    attrs: {
                        portBody: {
                            magnet: true,
                            fill: '#61549c',
                            strokeWidth: 0
                        },
                        portLabel: {
                            fontSize: 11,
                            fill: '#61549c',
                            fontWeight: 800
                        }
                    },
                    label: {
                        position: {
                            name: 'right',
                            args: {
                                y: 0
                            }
                        }
                    }
                }
            }
        }
    }, {
        portLabelMarkup: [{
            tagName: 'text',
            selector: 'portLabel'
        }]
    });

    joint.shapes.standard.Link.define('app.Link', {
        router: {
            name: 'normal'
        },
        connector: {
            name: 'rounded'
        },
        labels: [],
        attrs: {
            line: {
                stroke: '#8f8f8f',
                strokeDasharray: '0',
                strokeWidth: 2,
                fill: 'none',
                sourceMarker: {
                    type: 'path',
                    d: 'M 0 0 0 0',
                    stroke: 'none'
                },
                targetMarker: {
                    type: 'path',
                    d: 'M 0 -5 -10 0 0 5 z',
                    stroke: 'none'
                }
            }
        }
    }, {
        defaultLabel: {
            attrs: {
                rect: {
                    fill: '#ffffff',
                    stroke: '#8f8f8f',
                    strokeWidth: 1,
                    width: 'calc(w + 10)',
                    height: 'calc(h + 10)',
                    x: -5,
                    y: -5
                }
            }
        },

        getMarkerWidth: function(type) {
            var d = (type === 'source') ? this.attr('line/sourceMarker/d') : this.attr('line/targetMarker/d');
            return this.getDataWidth(d);
        },

        getDataWidth: function(d) {

            if (cache.has(d)) {
                return cache.get(d);
            } else {
                var bbox = (new g.Path(d)).bbox();
                cache.set(d, bbox ? bbox.width : 0);
                return cache.get(d);
            }
        }
    }, {

        connectionPoint: function(line, view, magnet, _opt, type, linkView) {
            var link = linkView.model;
            var markerWidth = (link.get('type') === 'app.Link') ? link.getMarkerWidth(type) : 0;
            var opt = { offset: markerWidth, stroke: true };
            // connection point for UML shapes lies on the root group containing all the shapes components
            var modelType = view.model.get('type');
            if (modelType.indexOf('uml') === 0) opt.selector = 'root';
            // taking the border stroke-width into account
            if (modelType === 'standard.InscribedImage') opt.selector = 'border';
            return joint.connectionPoints.boundary.call(this, line, view, magnet, opt, type, linkView);
        }
    });

})(joint);
