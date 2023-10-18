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

    App.config.stencil = {};

    App.config.stencil.groups = {
        // standard: { index: 1, label: 'Standard shapes' },
        // fsa: { index: 2, label: 'State machine' },
        // pn: { index: 3, label: 'Petri nets' },
        // erd: { index: 4, label: 'Entity-relationship' },
        uml: { index: 1, label: 'SECUENCIA' },
        // org: { index: 6, label: 'ORG' },
    };

    App.config.stencil.shapes = {};

    // App.config.stencil.shapes.uml = [
    //     {
    //         type: 'uml.Class',
    //         name: 'Class',
    //         attributes: ['+attr1'],
    //         methods: ['-setAttr1()'],
    //         size: { width: 90, height: 60 },
    //         attrs: {
    //             root: {
    //                 dataTooltip: 'Class',
    //                 dataTooltipPosition: 'left',
    //                 dataTooltipPositionSelector: '.joint-stencil'
    //             },
    //             '.uml-class-name-rect': {
    //                 top: 2,
    //                 fill: '#61549c',
    //                 stroke: '#f6f6f6',
    //                 'stroke-width': 1,
    //                 rx: 8,
    //                 ry: 8
    //             },
    //             '.uml-class-attrs-rect': {
    //                 top: 2,
    //                 fill: '#61549c',
    //                 stroke: '#f6f6f6',
    //                 'stroke-width': 1,
    //                 rx: 8,
    //                 ry: 8
    //             },
    //             '.uml-class-methods-rect': {
    //                 top: 2,
    //                 fill: '#61549c',
    //                 stroke: '#f6f6f6',
    //                 'stroke-width': 1,
    //                 rx: 8,
    //                 ry: 8
    //             },
    //             '.uml-class-name-text': {
    //                 ref: '.uml-class-name-rect',
    //                 'ref-y': 0.5,
    //                 'y-alignment': 'middle',
    //                 fill: '#f6f6f6',
    //                 'font-family': 'Roboto Condensed',
    //                 'font-weight': 'Normal',
    //                 'font-size': 11
    //             },
    //             '.uml-class-attrs-text': {
    //                 ref: '.uml-class-attrs-rect',
    //                 'ref-y': 0.5,
    //                 'y-alignment': 'middle',
    //                 fill: '#f6f6f6',
    //                 'font-family': 'Roboto Condensed',
    //                 'font-weight': 'Normal',
    //                 'font-size': 11
    //             },
    //             '.uml-class-methods-text': {
    //                 ref: '.uml-class-methods-rect',
    //                 'ref-y': 0.5,
    //                 'y-alignment': 'middle',
    //                 fill: '#f6f6f6',
    //                 'font-family': 'Roboto Condensed',
    //                 'font-weight': 'Normal',
    //                 'font-size': 11
    //             }
    //         }
    //     },
    //     {
    //         type: 'uml.Interface',
    //         name: 'Interface',
    //         attributes: ['+attr1'],
    //         methods: ['-setAttr1()'],
    //         size: { width: 90, height: 60 },
    //         attrs: {
    //             root: {
    //                 dataTooltip: 'Interface',
    //                 dataTooltipPosition: 'left',
    //                 dataTooltipPositionSelector: '.joint-stencil'
    //             },
    //             '.uml-class-name-rect': {
    //                 fill: '#fe854f',
    //                 stroke: '#f6f6f6',
    //                 'stroke-width': 1,
    //                 rx: 8,
    //                 ry: 8
    //             },
    //             '.uml-class-attrs-rect': {
    //                 fill: '#fe854f',
    //                 stroke: '#f6f6f6',
    //                 'stroke-width': 1,
    //                 rx: 8,
    //                 ry: 8
    //             },
    //             '.uml-class-methods-rect': {
    //                 fill: '#fe854f',
    //                 stroke: '#f6f6f6',
    //                 'stroke-width': 1,
    //                 rx: 8,
    //                 ry: 8
    //             },
    //             '.uml-class-name-text': {
    //                 ref: '.uml-class-name-rect',
    //                 'ref-y': 0.5,
    //                 'y-alignment': 'middle',
    //                 fill: '#f6f6f6',
    //                 'font-family': 'Roboto Condensed',
    //                 'font-weight': 'Normal',
    //                 'font-size': 11
    //             },
    //             '.uml-class-attrs-text': {
    //                 ref: '.uml-class-attrs-rect',
    //                 'ref-y': 0.5,
    //                 'y-alignment': 'middle',
    //                 fill: '#f6f6f6',
    //                 'font-family': 'Roboto Condensed',
    //                 'font-size': 11
    //             },
    //             '.uml-class-methods-text': {
    //                 ref: '.uml-class-methods-rect',
    //                 'ref-y': 0.5,
    //                 'y-alignment': 'middle',
    //                 fill: '#f6f6f6',
    //                 'font-family': 'Roboto Condensed',
    //                 'font-weight': 'Normal',
    //                 'font-size': 11
    //             }
    //         }
    //     },
    //     {
    //         type: 'uml.Abstract',
    //         name: 'Abstract',
    //         attributes: ['+attr1'],
    //         methods: ['-setAttr1()'],
    //         size: { width: 90, height: 60 },
    //         attrs: {
    //             root: {
    //                 dataTooltip: 'Abstract',
    //                 dataTooltipPosition: 'left',
    //                 dataTooltipPositionSelector: '.joint-stencil'
    //             },
    //             '.uml-class-name-rect': {
    //                 fill: '#6a6c8a',
    //                 stroke: '#f6f6f6',
    //                 'stroke-width': 1,
    //                 rx: 8,
    //                 ry: 8
    //             },
    //             '.uml-class-attrs-rect': {
    //                 fill: '#6a6c8a',
    //                 stroke: '#f6f6f6',
    //                 'stroke-width': 1,
    //                 rx: 8,
    //                 ry: 8
    //             },
    //             '.uml-class-methods-rect': {
    //                 fill: '#6a6c8a',
    //                 stroke: '#f6f6f6',
    //                 'stroke-width': 1,
    //                 rx: 8,
    //                 ry: 8
    //             },
    //             '.uml-class-name-text': {
    //                 ref: '.uml-class-name-rect',
    //                 'ref-y': 0.5,
    //                 'y-alignment': 'middle',
    //                 fill: '#f6f6f6',
    //                 'font-family': 'Roboto Condensed',
    //                 'font-weight': 'Normal',
    //                 'font-size': 11
    //             },
    //             '.uml-class-attrs-text': {
    //                 ref: '.uml-class-attrs-rect',
    //                 'ref-y': 0.5,
    //                 'y-alignment': 'middle',
    //                 fill: '#f6f6f6',
    //                 'font-family': 'Roboto Condensed',
    //                 'font-weight': 'Normal',
    //                 'font-size': 11
    //             },
    //             '.uml-class-methods-text': {
    //                 ref: '.uml-class-methods-rect',
    //                 'ref-y': 0.5,
    //                 'y-alignment': 'middle',
    //                 fill: '#f6f6f6',
    //                 'font-family': 'Roboto Condensed',
    //                 'font-weight': 'Normal',
    //                 'font-size': 11
    //             }
    //         }
    //     },

    //     {
    //         type: 'uml.State',
    //         name: 'State',
    //         events: ['entry/', 'create()'],
    //         size: { width: 90, height: 60 },
    //         attrs: {
    //             root: {
    //                 dataTooltip: 'State',
    //                 dataTooltipPosition: 'left',
    //                 dataTooltipPositionSelector: '.joint-stencil'
    //             },
    //             '.uml-state-body': {
    //                 fill: '#feb663',
    //                 stroke: '#f6f6f6',
    //                 'stroke-width': 1,
    //                 rx: 8,
    //                 ry: 8,
    //                 'stroke-dasharray': '0'
    //             },
    //             '.uml-state-separator': {
    //                 stroke: '#f6f6f6',
    //                 'stroke-width': 1,
    //                 'stroke-dasharray': '0'
    //             },
    //             '.uml-state-name': {
    //                 fill: '#f6f6f6',
    //                 'font-size': 11,
    //                 'font-family': 'Roboto Condensed',
    //                 'font-weight': 'Normal'
    //             },
    //             '.uml-state-events': {
    //                 fill: '#f6f6f6',
    //                 'font-size': 11,
    //                 'font-family': 'Roboto Condensed',
    //                 'font-weight': 'Normal'
    //             }
    //         }
    //     }
    // ];

    App.config.stencil.shapes.uml = [
        {
            type: 'standard.Actor', // Utilizamos la forma 'Actor' definida en joint.shapes.standard.Actor
            name: 'Actor',
            size: { width: 100, height: 40 },
            attrs: {
                'rect': { fill: 'white', stroke: 'black' },
                'text': { text: 'Actor', 'font-size': 14 }
            }
        },
        {
            type: 'standard.Entity', // Utilizamos la forma 'Actor' definida en joint.shapes.standard.Actor
            name: 'Actor',
            size: { width: 50, height: 40 },
            attrs: {
                'ellipse': { fill: 'white', stroke: 'black' },
                'text': { text: 'Entity', 'font-size': 14 }
            }
        },
        {
            type: 'standard.Boundary', // Utilizamos la forma 'Actor' definida en joint.shapes.standard.Actor
            name: 'Actor',
            size: { width: 50, height: 40 },
            attrs: {
                'ellipse': { fill: 'white', stroke: 'black' },
                'text': { text: 'Boundary', 'font-size': 14 }
            }
        },
        {
            type: 'standard.Control', // Utilizamos la forma 'Actor' definida en joint.shapes.standard.Actor
            name: 'Actor',
            size: { width: 50, height: 40 },
            attrs: {
                'ellipse': { fill: 'white', stroke: 'black' },
                'text': { text: 'Control', 'font-size': 14 }
            }
        },
        {
            type: 'standard.Object', // Utilizamos la forma 'Actor' definida en joint.shapes.standard.Actor
            name: 'Actor',
            size: { width: 100, height: 40 },
            attrs: {
                'rect': { fill: 'white', stroke: 'black' },
                'text': { text: 'Object', 'font-size': 14 }
            }
        },
    ];

})();
