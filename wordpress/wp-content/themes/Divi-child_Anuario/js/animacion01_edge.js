/**
 * Adobe Edge: symbol definitions
 */
(function($, Edge, compId){
//images folder
var im='images/';

var fonts = {};    fonts['doppio-one, sans-serif']='<script src=\"http://use.edgefonts.net/doppio-one:n4:all.js\"></script>';
    fonts['open-sans, sans-serif']='<script src=\"http://use.edgefonts.net/open-sans:n7,i7,n8,i8,i4,n3,i3,n4,n6,i6:all.js\"></script>';

var opts = {};
var resources = [
];
var symbols = {
"stage": {
    version: "3.0.0",
    minimumCompatibleVersion: "3.0.0",
    build: "3.0.0.322",
    baseState: "Base State",
    scaleToFit: "width",
    centerStage: "none",
    initialState: "Base State",
    gpuAccelerate: false,
    resizeInstances: false,
    content: {
            dom: [
            {
                id: 'fondo01',
                type: 'image',
                rect: ['0px', '0px','1024px','768px','auto', 'auto'],
                fill: ["rgba(0,0,0,0)",im+"fondo01.svg",'0px','0px']
            },
            {
                id: 'forma032',
                type: 'image',
                rect: ['-56px', '-111px','1440px','1080px','auto', 'auto'],
                fill: ["rgba(0,0,0,0)",im+"forma03.svg",'0px','0px']
            },
            {
                id: 'forma032Copy',
                type: 'image',
                rect: ['-56px', '-111px','1440px','1080px','auto', 'auto'],
                fill: ["rgba(0,0,0,0)",im+"forma03.svg",'0px','0px']
            },
            {
                id: 'forma032Copy2',
                type: 'image',
                rect: ['-56px', '-111px','1440px','1080px','auto', 'auto'],
                fill: ["rgba(0,0,0,0)",im+"forma03.svg",'0px','0px']
            },
            {
                id: 'forma032Copy3',
                type: 'image',
                rect: ['-56px', '-111px','1440px','1080px','auto', 'auto'],
                fill: ["rgba(0,0,0,0)",im+"forma03.svg",'0px','0px']
            },
            {
                id: 'forma01Copy',
                type: 'image',
                rect: ['-236px', '-131px','1440px','1080px','auto', 'auto'],
                fill: ["rgba(0,0,0,0)",im+"forma01.svg",'0px','0px','100%','100%']
            },
            {
                id: 'forma01Copy2',
                type: 'image',
                rect: ['-236px', '-131px','1440px','1080px','auto', 'auto'],
                fill: ["rgba(0,0,0,0)",im+"forma01.svg",'0px','0px','100%','100%']
            },
            {
                id: 'forma01Copy3',
                type: 'image',
                rect: ['-236px', '-131px','1440px','1080px','auto', 'auto'],
                fill: ["rgba(0,0,0,0)",im+"forma01.svg",'0px','0px','100%','100%']
            },
            {
                id: 'forma012',
                type: 'image',
                tag: 'img',
                rect: ['-236px', '-136px','1440px','1080px','auto', 'auto'],
                fill: ["rgba(0,0,0,0)",im+"forma01.svg",'0px','0px']
            },
            {
                id: 'doppio-onesans-serif',
                type: 'text',
                rect: ['207px', '356px','auto','auto','auto', 'auto'],
                text: "EPICENTRO",
                align: "left",
                font: ['open-sans, sans-serif', 43, "rgba(0,0,0,1)", "400", "none", "normal"]
            },
            {
                id: 'Symbol_1',
                type: 'rect',
                rect: ['730', '379','auto','auto','auto', 'auto']
            }],
            symbolInstances: [
            {
                id: 'Symbol_1',
                symbolName: 'Symbol_1',
                autoPlay: {

                }
            }
            ]
        },
    states: {
        "Base State": {
            "${_forma032}": [
                ["style", "top", '-111px'],
                ["style", "right", 'auto'],
                ["transform", "scaleY", '0.2'],
                ["style", "bottom", 'auto'],
                ["transform", "scaleX", '0.2'],
                ["style", "opacity", '0'],
                ["style", "left", '-56px'],
                ["style", "width", '1080px']
            ],
            "${_forma012}": [
                ["style", "top", '-136px'],
                ["style", "opacity", '0.000000'],
                ["style", "left", '-236px']
            ],
            "${_forma01Copy2}": [
                ["style", "top", '-131px'],
                ["transform", "scaleY", '0.2'],
                ["transform", "scaleX", '0.2'],
                ["style", "opacity", '0'],
                ["style", "left", '-236px']
            ],
            "${_forma032Copy3}": [
                ["style", "top", '-111px'],
                ["style", "opacity", '0'],
                ["transform", "scaleY", '0.2'],
                ["style", "bottom", 'auto'],
                ["transform", "scaleX", '0.2'],
                ["style", "right", 'auto'],
                ["style", "left", '-56px'],
                ["style", "width", '1080px']
            ],
            "${_forma01Copy3}": [
                ["style", "top", '-131px'],
                ["transform", "scaleY", '0.2'],
                ["transform", "scaleX", '0.2'],
                ["style", "opacity", '0'],
                ["style", "left", '-236px']
            ],
            "${_Symbol_1}": [
                ["style", "opacity", '0.000000']
            ],
            "${_forma032Copy}": [
                ["style", "top", '-111px'],
                ["style", "right", 'auto'],
                ["transform", "scaleY", '0.2'],
                ["style", "bottom", 'auto'],
                ["transform", "scaleX", '0.2'],
                ["style", "opacity", '0'],
                ["style", "left", '-56px'],
                ["style", "width", '1080px']
            ],
            "${_doppio-onesans-serif}": [
                ["style", "top", '356px'],
                ["style", "font-family", 'open-sans, sans-serif'],
                ["style", "opacity", '0'],
                ["style", "left", '207px'],
                ["style", "font-size", '43px']
            ],
            "${_forma01Copy}": [
                ["style", "top", '-131px'],
                ["transform", "scaleY", '0.2'],
                ["transform", "scaleX", '0.2'],
                ["style", "opacity", '0'],
                ["style", "left", '-236px']
            ],
            "${_fondo01}": [
                ["style", "top", '0px'],
                ["style", "height", '768px'],
                ["style", "left", '0px'],
                ["style", "width", '1024px']
            ],
            "${_forma032Copy2}": [
                ["style", "top", '-111px'],
                ["style", "right", 'auto'],
                ["transform", "scaleY", '0.2'],
                ["style", "bottom", 'auto'],
                ["transform", "scaleX", '0.2'],
                ["style", "opacity", '0'],
                ["style", "left", '-56px'],
                ["style", "width", '1080px']
            ],
            "${_Stage}": [
                ["color", "background-color", 'rgba(255,255,255,1)'],
                ["style", "width", '1024px'],
                ["style", "height", '768px'],
                ["style", "overflow", 'hidden']
            ]
        }
    },
    timelines: {
        "Default Timeline": {
            fromState: "Base State",
            toState: "",
            duration: 5500,
            autoPlay: true,
            timeline: [
                { id: "eid40", tween: [ "style", "${_forma032Copy}", "opacity", '1', { fromValue: '0'}], position: 1500, duration: 1000 },
                { id: "eid41", tween: [ "style", "${_forma032Copy}", "opacity", '0', { fromValue: '1'}], position: 2500, duration: 1000 },
                { id: "eid18", tween: [ "style", "${_forma032}", "opacity", '1', { fromValue: '0'}], position: 500, duration: 1000 },
                { id: "eid29", tween: [ "style", "${_forma032}", "opacity", '0', { fromValue: '1'}], position: 1500, duration: 1000 },
                { id: "eid38", tween: [ "transform", "${_forma032Copy}", "scaleY", '1', { fromValue: '0.2'}], position: 1500, duration: 2000 },
                { id: "eid136", tween: [ "style", "${_forma012}", "opacity", '1', { fromValue: '0.000000'}], position: 500, duration: 500 },
                { id: "eid141", tween: [ "style", "${_forma012}", "opacity", '0', { fromValue: '1'}], position: 1000, duration: 500 },
                { id: "eid142", tween: [ "style", "${_forma012}", "opacity", '1', { fromValue: '0.000000'}], position: 1500, duration: 500 },
                { id: "eid143", tween: [ "style", "${_forma012}", "opacity", '0', { fromValue: '1'}], position: 2000, duration: 500 },
                { id: "eid144", tween: [ "style", "${_forma012}", "opacity", '1', { fromValue: '0.000000'}], position: 2500, duration: 500 },
                { id: "eid145", tween: [ "style", "${_forma012}", "opacity", '0', { fromValue: '1'}], position: 3000, duration: 500 },
                { id: "eid146", tween: [ "style", "${_forma012}", "opacity", '1', { fromValue: '0.000000'}], position: 3500, duration: 500 },
                { id: "eid147", tween: [ "style", "${_forma012}", "opacity", '0', { fromValue: '1'}], position: 4000, duration: 500 },
                { id: "eid157", tween: [ "style", "${_forma012}", "opacity", '1', { fromValue: '0.000000'}], position: 4500, duration: 500 },
                { id: "eid156", tween: [ "style", "${_forma012}", "opacity", '0', { fromValue: '1'}], position: 5000, duration: 500 },
                { id: "eid166", tween: [ "style", "${_forma012}", "opacity", '0.000000', { fromValue: '0.000000'}], position: 5500, duration: 0 },
                { id: "eid82", tween: [ "style", "${_forma032}", "top", '-111px', { fromValue: '-111px'}], position: 1500, duration: 0 },
                { id: "eid79", tween: [ "style", "${_forma032Copy}", "left", '-56px', { fromValue: '-56px'}], position: 2500, duration: 0 },
                { id: "eid182", tween: [ "style", "${_Symbol_1}", "opacity", '1', { fromValue: '0.000000'}], position: 1500, duration: 1000 },
                { id: "eid184", tween: [ "style", "${_Symbol_1}", "opacity", '0.5', { fromValue: '1'}], position: 2500, duration: 1000 },
                { id: "eid186", tween: [ "style", "${_Symbol_1}", "opacity", '1', { fromValue: '0.500000'}], position: 3500, duration: 1000 },
                { id: "eid188", tween: [ "style", "${_Symbol_1}", "opacity", '0', { fromValue: '1'}], position: 4500, duration: 1000 },
                { id: "eid158", tween: [ "style", "${_forma032Copy3}", "top", '-111px', { fromValue: '-111px'}], position: 4500, duration: 0 },
                { id: "eid39", tween: [ "transform", "${_forma032Copy}", "scaleX", '1', { fromValue: '0.2'}], position: 1500, duration: 2000 },
                { id: "eid50", tween: [ "style", "${_forma032Copy2}", "width", '1080px', { fromValue: '1080px'}], position: 3500, duration: 0 },
                { id: "eid81", tween: [ "style", "${_forma032}", "left", '-56px', { fromValue: '-56px'}], position: 1500, duration: 0 },
                { id: "eid161", tween: [ "style", "${_forma032Copy3}", "opacity", '1', { fromValue: '0'}], position: 3500, duration: 1000 },
                { id: "eid162", tween: [ "style", "${_forma032Copy3}", "opacity", '0', { fromValue: '1'}], position: 4500, duration: 1000 },
                { id: "eid159", tween: [ "transform", "${_forma032Copy3}", "scaleY", '1', { fromValue: '0.2'}], position: 3500, duration: 2000 },
                { id: "eid169", tween: [ "style", "${_doppio-onesans-serif}", "opacity", '1', { fromValue: '0'}], position: 500, duration: 2000 },
                { id: "eid172", tween: [ "style", "${_doppio-onesans-serif}", "opacity", '0', { fromValue: '1'}], position: 4000, duration: 1500 },
                { id: "eid164", tween: [ "style", "${_forma032Copy3}", "width", '1080px', { fromValue: '1080px'}], position: 4500, duration: 0 },
                { id: "eid19", tween: [ "style", "${_forma032}", "width", '1080px', { fromValue: '1080px'}], position: 1500, duration: 0 },
                { id: "eid46", tween: [ "transform", "${_forma032Copy2}", "scaleX", '1', { fromValue: '0.2'}], position: 2500, duration: 2000 },
                { id: "eid47", tween: [ "style", "${_forma032Copy2}", "opacity", '1', { fromValue: '0'}], position: 2500, duration: 1000 },
                { id: "eid48", tween: [ "style", "${_forma032Copy2}", "opacity", '0', { fromValue: '1'}], position: 3500, duration: 1000 },
                { id: "eid45", tween: [ "transform", "${_forma032Copy2}", "scaleY", '1', { fromValue: '0.2'}], position: 2500, duration: 2000 },
                { id: "eid26", tween: [ "transform", "${_forma032}", "scaleX", '1', { fromValue: '0.2'}], position: 500, duration: 2000 },
                { id: "eid78", tween: [ "style", "${_forma032Copy2}", "top", '-111px', { fromValue: '-111px'}], position: 3500, duration: 0 },
                { id: "eid77", tween: [ "style", "${_forma032Copy2}", "left", '-56px', { fromValue: '-56px'}], position: 3500, duration: 0 },
                { id: "eid163", tween: [ "style", "${_forma032Copy3}", "left", '-56px', { fromValue: '-56px'}], position: 4500, duration: 0 },
                { id: "eid80", tween: [ "style", "${_forma032Copy}", "top", '-111px', { fromValue: '-111px'}], position: 2500, duration: 0 },
                { id: "eid160", tween: [ "transform", "${_forma032Copy3}", "scaleX", '1', { fromValue: '0.2'}], position: 3500, duration: 2000 },
                { id: "eid43", tween: [ "style", "${_forma032Copy}", "width", '1080px', { fromValue: '1080px'}], position: 2500, duration: 0 },
                { id: "eid27", tween: [ "transform", "${_forma032}", "scaleY", '1', { fromValue: '0.2'}], position: 500, duration: 2000 }            ]
        }
    }
},
"Symbol_1": {
    version: "3.0.0",
    minimumCompatibleVersion: "3.0.0",
    build: "3.0.0.322",
    baseState: "Base State",
    scaleToFit: "none",
    centerStage: "none",
    initialState: "Base State",
    gpuAccelerate: false,
    resizeInstances: false,
    content: {
            dom: [
                {
                    type: 'rect',
                    id: 'Rectangle',
                    stroke: [0, 'rgba(0,0,0,1)', 'none'],
                    rect: ['0px', '1px', '228px', '164px', 'auto', 'auto'],
                    fill: ['rgba(0,84,140,1.00)']
                },
                {
                    font: ['open-sans, sans-serif', 28, 'rgba(255,255,255,1.00)', 'normal', 'none', ''],
                    type: 'text',
                    id: 'Text',
                    text: 'Escala Richter',
                    align: 'center',
                    rect: ['6px', '118px', '216px', '28px', 'auto', 'auto']
                },
                {
                    font: ['open-sans, sans-serif', 72, 'rgba(255,255,255,1.00)', 'normal', 'none', ''],
                    type: 'text',
                    id: 'TextCopy',
                    text: '5,1',
                    align: 'center',
                    rect: ['6px', '1px', '216px', '101px', 'auto', 'auto']
                },
                {
                    type: 'rect',
                    id: 'Rectangle2',
                    stroke: [0, 'rgb(0, 0, 0)', 'none'],
                    rect: ['23px', '108px', '179px', '3px', 'auto', 'auto'],
                    fill: ['rgba(247,237,217,1.00)']
                }
            ],
            symbolInstances: [
            ]
        },
    states: {
        "Base State": {
            "${_Rectangle2}": [
                ["style", "height", '3px'],
                ["style", "top", '106px'],
                ["style", "left", '23px'],
                ["color", "background-color", 'rgba(247,237,217,1.00)']
            ],
            "${_Text}": [
                ["style", "top", '116px'],
                ["style", "width", '216px'],
                ["style", "text-align", 'center'],
                ["style", "height", '28px'],
                ["color", "color", 'rgba(255,255,255,1.00)'],
                ["style", "font-family", 'open-sans, sans-serif'],
                ["style", "left", '6px'],
                ["style", "font-size", '28px']
            ],
            "${symbolSelector}": [
                ["style", "height", '165px'],
                ["style", "width", '228px']
            ],
            "${_Rectangle}": [
                ["style", "top", '1px'],
                ["style", "height", '164px'],
                ["color", "background-color", 'rgba(0,84,140,1.00)'],
                ["style", "left", '0px'],
                ["style", "width", '228px']
            ],
            "${_TextCopy}": [
                ["style", "top", '0px'],
                ["style", "font-size", '72px'],
                ["style", "text-align", 'center'],
                ["color", "color", 'rgba(255,255,255,1)'],
                ["style", "height", '101px'],
                ["style", "font-family", 'open-sans, sans-serif'],
                ["style", "left", '6px'],
                ["style", "width", '216px']
            ]
        }
    },
    timelines: {
        "Default Timeline": {
            fromState: "Base State",
            toState: "",
            duration: 2500,
            autoPlay: true,
            timeline: [
                { id: "eid179", tween: [ "style", "${_TextCopy}", "top", '0px', { fromValue: '0px'}], position: 2500, duration: 0 },
                { id: "eid177", tween: [ "style", "${_Rectangle2}", "top", '106px', { fromValue: '106px'}], position: 2500, duration: 0 },
                { id: "eid178", tween: [ "style", "${_Text}", "top", '116px', { fromValue: '116px'}], position: 2500, duration: 0 }            ]
        }
    }
}
};


Edge.registerCompositionDefn(compId, symbols, fonts, resources, opts);

/**
 * Adobe Edge DOM Ready Event Handler
 */
$(window).ready(function() {
     Edge.launchComposition(compId);
});
})(jQuery, AdobeEdge, "EDGE-21281270");
