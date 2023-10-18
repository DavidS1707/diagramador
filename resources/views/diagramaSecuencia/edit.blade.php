<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, viewport-fit=cover" />
    <meta name="description" content="A sequence diagram editor." />
    <link rel="stylesheet" href="{{ asset('assets/style.css') }}" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Copyright 1998-2023 by Northwoods Software Corporation. -->
    <title>Examen Parcial SW1</title>
</head>

<body>
    <!-- This top nav is not part of the sample code -->
    <nav id="navTop" class="w-full z-30 top-0 text-white bg-nwoods-primary">
        <div
            class="w-full container max-w-screen-lg mx-auto flex flex-wrap sm:flex-nowrap items-center justify-between mt-0 py-2">
            <div class="md:pl-4">
                <a
                    class="text-white hover:text-white no-underline hover:no-underline
        font-bold text-2xl lg:text-4xl rounded-lg hover:bg-nwoods-secondary ">
                    <h1 class="my-0 p-1 ">Diagrama</h1>
                </a>
            </div>
            <button id="topnavButton" class="rounded-lg sm:hidden focus:outline-none focus:ring"
                aria-label="Navigation">
                <svg fill="currentColor" viewBox="0 0 20 20" class="w-6 h-6">
                    <path id="topnavOpen" fill-rule="evenodd"
                        d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM9 15a1 1 0 011-1h6a1 1 0 110 2h-6a1 1 0 01-1-1z"
                        clip-rule="evenodd"></path>
                    <path id="topnavClosed" class="hidden" fill-rule="evenodd"
                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                        clip-rule="evenodd"></path>
                </svg>
            </button>
            <div id="topnavList" class="hidden sm:block items-center w-auto mt-0 text-white p-0 z-20">
                <ul
                    class="list-reset list-none font-semibold flex justify-end flex-wrap sm:flex-nowrap items-center px-0 pb-0">
                    <li class="p-1 sm:p-0"><a class="topnav-link"
                            href="{{ route('diagramaSecuencia.index') }}">Volver</a></li>
                    <li class="p-1 sm:p-0"><button onclick="generateCode()">Generar Código</button></li>
                    {{-- <li class="p-1 sm:p-0"><button onclick="generateCode('python')">Generar Código Python</button></li>
                    <li class="p-1 sm:p-0"><button onclick="generateCode('javascript')">Generar Código
                            JavaScript</button></li> --}}
                </ul>
            </div>
        </div>
        <hr class="border-b border-gray-600 opacity-50 my-0 py-0" />
    </nav>
    <div class="md:flex flex-col md:flex-row md:min-h-screen w-full max-w-screen-xl mx-auto">
        <div id="navSide" class="flex flex-col w-full md:w-48 text-gray-700 bg-white flex-shrink-0"></div>
        <!-- * * * * * * * * * * * * * -->
        <!-- Start of GoJS sample code -->

        <script src="{{ asset('release/go.js') }}"></script>
        <div id="allSampleContent" class="p-4 w-full">
            <link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro' rel='stylesheet' type='text/css'>
            <script id="code">
                function init() {

                    // Since 2.2 you can also author concise templates with method chaining instead of GraphObject.make
                    // For details, see https://gojs.net/latest/intro/buildingObjects.html
                    const $ = go.GraphObject.make;

                    myDiagram =
                        new go.Diagram("myDiagramDiv", // must be the ID or reference to an HTML DIV
                            {
                                allowCopy: false,
                                linkingTool: $(MessagingTool), // defined below
                                "resizingTool.isGridSnapEnabled": true,
                                draggingTool: $(MessageDraggingTool), // defined below
                                "draggingTool.gridSnapCellSize": new go.Size(1, MessageSpacing / 4),
                                "draggingTool.isGridSnapEnabled": true,
                                // automatically extend Lifelines as Activities are moved or resized
                                "SelectionMoved": ensureLifelineHeights,
                                "PartResized": ensureLifelineHeights,
                                "undoManager.isEnabled": true
                            });

                    // when the document is modified, add a "*" to the title and enable the "Save" button
                    myDiagram.addDiagramListener("Modified", e => {
                        const button = document.getElementById("SaveButton");
                        if (button) button.disabled = !myDiagram.isModified;
                        const idx = document.title.indexOf("*");
                        if (myDiagram.isModified) {
                            if (idx < 0) document.title += "*";
                        } else {
                            if (idx >= 0) document.title = document.title.slice(0, idx);
                        }
                    });

                    // define the Lifeline Node template.
                    myDiagram.groupTemplate =
                        $(go.Group, "Vertical", {
                                locationSpot: go.Spot.Bottom,
                                locationObjectName: "HEADER",
                                minLocation: new go.Point(0, 0),
                                maxLocation: new go.Point(9999, 0),
                                selectionObjectName: "HEADER"
                            },
                            new go.Binding("location", "loc", go.Point.parse).makeTwoWay(go.Point.stringify),
                            $(go.Panel, "Auto", {
                                    name: "HEADER"
                                },
                                $(go.Shape, "Rectangle", {
                                    fill: $(go.Brush, "Linear", {
                                        0: "#bbdefb",
                                        1: go.Brush.darkenBy("#bbdefb", 0.1)
                                    }),
                                    stroke: null
                                }),
                                $(go.TextBlock, {
                                        margin: 5,
                                        font: "400 10pt Source Sans Pro, sans-serif",
                                        editable: true,
                                    },
                                    new go.Binding("text", "text"))
                            ),
                            $(go.Shape, {
                                    figure: "LineV",
                                    fill: null,
                                    stroke: "gray",
                                    strokeDashArray: [3, 3],
                                    width: 1,
                                    alignment: go.Spot.Center,
                                    portId: "",
                                    fromLinkable: true,
                                    fromLinkableDuplicates: true,
                                    toLinkable: true,
                                    toLinkableDuplicates: true,
                                    cursor: "pointer"
                                },
                                new go.Binding("height", "duration", computeLifelineHeight))
                        );

                    // define the Activity Node template
                    myDiagram.nodeTemplate =
                        $(go.Node, {
                                locationSpot: go.Spot.Top,
                                locationObjectName: "SHAPE",
                                minLocation: new go.Point(NaN, LinePrefix - ActivityStart),
                                maxLocation: new go.Point(NaN, 19999),
                                selectionObjectName: "SHAPE",
                                resizable: true,
                                resizeObjectName: "SHAPE",
                                resizeAdornmentTemplate: $(go.Adornment, "Spot",
                                    $(go.Placeholder),
                                    $(go.Shape, // only a bottom resize handle
                                        {
                                            alignment: go.Spot.Bottom,
                                            cursor: "col-resize",
                                            desiredSize: new go.Size(6, 6),
                                            fill: "yellow"
                                        })
                                )
                            },
                            new go.Binding("location", "", computeActivityLocation).makeTwoWay(backComputeActivityLocation),
                            $(go.Shape, "Rectangle", {
                                    name: "SHAPE",
                                    fill: "white",
                                    stroke: "black",
                                    width: ActivityWidth,
                                    // allow Activities to be resized down to 1/4 of a time unit
                                    minSize: new go.Size(ActivityWidth, computeActivityHeight(0.25))
                                },
                                new go.Binding("height", "duration", computeActivityHeight).makeTwoWay(backComputeActivityHeight))
                        );

                    // define the Message Link template.
                    myDiagram.linkTemplate =
                        $(MessageLink, // defined below
                            {
                                selectionAdorned: true,
                                curviness: 0
                            },
                            $(go.Shape, "Rectangle", {
                                stroke: "black"
                            }),
                            $(go.Shape, {
                                toArrow: "OpenTriangle",
                                stroke: "black"
                            }),
                            $(go.TextBlock, {
                                    font: "400 9pt Source Sans Pro, sans-serif",
                                    segmentIndex: 0,
                                    segmentOffset: new go.Point(NaN, NaN),
                                    isMultiline: false,
                                    editable: true
                                },
                                new go.Binding("text", "text").makeTwoWay())
                        );

                    // create the graph by reading the JSON data saved in "mySavedModel" textarea element
                    load();
                }

                function ensureLifelineHeights(e) {
                    // iterate over all Activities (ignore Groups)
                    const arr = myDiagram.model.nodeDataArray;
                    let max = -1;
                    for (let i = 0; i < arr.length; i++) {
                        const act = arr[i];
                        if (act.isGroup) continue;
                        max = Math.max(max, act.start + act.duration);
                    }
                    if (max > 0) {
                        // now iterate over only Groups
                        for (let i = 0; i < arr.length; i++) {
                            const gr = arr[i];
                            if (!gr.isGroup) continue;
                            if (max > gr.duration) { // this only extends, never shrinks
                                myDiagram.model.setDataProperty(gr, "duration", max);
                            }
                        }
                    }
                }

                // some parameters
                const LinePrefix = 20; // vertical starting point in document for all Messages and Activations
                const LineSuffix = 30; // vertical length beyond the last message time
                const MessageSpacing = 20; // vertical distance between Messages at different steps
                const ActivityWidth = 10; // width of each vertical activity bar
                const ActivityStart = 5; // height before start message time
                const ActivityEnd = 5; // height beyond end message time

                function computeLifelineHeight(duration) {
                    return LinePrefix + duration * MessageSpacing + LineSuffix;
                }

                function computeActivityLocation(act) {
                    const groupdata = myDiagram.model.findNodeDataForKey(act.group);
                    if (groupdata === null) return new go.Point();
                    // get location of Lifeline's starting point
                    const grouploc = go.Point.parse(groupdata.loc);
                    return new go.Point(grouploc.x, convertTimeToY(act.start) - ActivityStart);
                }

                function backComputeActivityLocation(loc, act) {
                    myDiagram.model.setDataProperty(act, "start", convertYToTime(loc.y + ActivityStart));
                }

                function computeActivityHeight(duration) {
                    return ActivityStart + duration * MessageSpacing + ActivityEnd;
                }

                function backComputeActivityHeight(height) {
                    return (height - ActivityStart - ActivityEnd) / MessageSpacing;
                }

                // time is just an abstract small non-negative integer
                // here we map between an abstract time and a vertical position
                function convertTimeToY(t) {
                    return t * MessageSpacing + LinePrefix;
                }

                function convertYToTime(y) {
                    return (y - LinePrefix) / MessageSpacing;
                }


                // a custom routed Link
                class MessageLink extends go.Link {
                    constructor() {
                        super();
                        this.time = 0; // use this "time" value when this is the temporaryLink
                    }

                    getLinkPoint(node, port, spot, from, ortho, othernode, otherport) {
                        const p = port.getDocumentPoint(go.Spot.Center);
                        const r = port.getDocumentBounds();
                        const op = otherport.getDocumentPoint(go.Spot.Center);

                        const data = this.data;
                        const time = data !== null ? data.time : this
                            .time; // if not bound, assume this has its own "time" property

                        const aw = this.findActivityWidth(node, time);
                        const x = (op.x > p.x ? p.x + aw / 2 : p.x - aw / 2);
                        const y = convertTimeToY(time);
                        return new go.Point(x, y);
                    }

                    findActivityWidth(node, time) {
                        let aw = ActivityWidth;
                        if (node instanceof go.Group) {
                            // see if there is an Activity Node at this point -- if not, connect the link directly with the Group's lifeline
                            if (!node.memberParts.any(mem => {
                                    const act = mem.data;
                                    return (act !== null && act.start <= time && time <= act.start + act.duration);
                                })) {
                                aw = 0;
                            }
                        }
                        return aw;
                    }

                    getLinkDirection(node, port, linkpoint, spot, from, ortho, othernode, otherport) {
                        const p = port.getDocumentPoint(go.Spot.Center);
                        const op = otherport.getDocumentPoint(go.Spot.Center);
                        const right = op.x > p.x;
                        return right ? 0 : 180;
                    }

                    computePoints() {
                        if (this.fromNode === this.toNode) { // also handle a reflexive link as a simple orthogonal loop
                            const data = this.data;
                            const time = data !== null ? data.time : this
                                .time; // if not bound, assume this has its own "time" property
                            const p = this.fromNode.port.getDocumentPoint(go.Spot.Center);
                            const aw = this.findActivityWidth(this.fromNode, time);

                            const x = p.x + aw / 2;
                            const y = convertTimeToY(time);
                            this.clearPoints();
                            this.addPoint(new go.Point(x, y));
                            this.addPoint(new go.Point(x + 50, y));
                            this.addPoint(new go.Point(x + 50, y + 5));
                            this.addPoint(new go.Point(x, y + 5));
                            return true;
                        } else {
                            return super.computePoints();
                        }
                    }
                }
                // end MessageLink


                // A custom LinkingTool that fixes the "time" (i.e. the Y coordinate)
                // for both the temporaryLink and the actual newly created Link
                class MessagingTool extends go.LinkingTool {
                    constructor() {
                        super();

                        // Since 2.2 you can also author concise templates with method chaining instead of GraphObject.make
                        // For details, see https://gojs.net/latest/intro/buildingObjects.html
                        const $ = go.GraphObject.make;
                        this.temporaryLink =
                            $(MessageLink,
                                $(go.Shape, "Rectangle", {
                                    stroke: "magenta",
                                    strokeWidth: 2
                                }),
                                $(go.Shape, {
                                    toArrow: "OpenTriangle",
                                    stroke: "magenta"
                                }));
                    }

                    doActivate() {
                        super.doActivate();
                        const time = convertYToTime(this.diagram.firstInput.documentPoint.y);
                        this.temporaryLink.time = Math.ceil(time); // round up to an integer value
                    }

                    insertLink(fromnode, fromport, tonode, toport) {
                        const newlink = super.insertLink(fromnode, fromport, tonode, toport);
                        if (newlink !== null) {
                            const model = this.diagram.model;
                            // specify the time of the message
                            const start = this.temporaryLink.time;
                            const duration = 1;
                            newlink.data.time = start;
                            model.setDataProperty(newlink.data, "text", "msg");
                            // and create a new Activity node data in the "to" group data
                            const newact = {
                                group: newlink.data.to,
                                start: start,
                                duration: duration
                            };
                            model.addNodeData(newact);
                            // now make sure all Lifelines are long enough
                            ensureLifelineHeights();
                        }
                        return newlink;
                    }
                }
                // end MessagingTool


                // A custom DraggingTool that supports dragging any number of MessageLinks up and down --
                // changing their data.time value.
                class MessageDraggingTool extends go.DraggingTool {
                    // override the standard behavior to include all selected Links,
                    // even if not connected with any selected Nodes
                    computeEffectiveCollection(parts, options) {
                        const result = super.computeEffectiveCollection(parts, options);
                        // add a dummy Node so that the user can select only Links and move them all
                        result.add(new go.Node(), new go.DraggingInfo(new go.Point()));
                        // normally this method removes any links not connected to selected nodes;
                        // we have to add them back so that they are included in the "parts" argument to moveParts
                        parts.each(part => {
                            if (part instanceof go.Link) {
                                result.add(part, new go.DraggingInfo(part.getPoint(0).copy()));
                            }
                        })
                        return result;
                    }

                    // override to allow dragging when the selection only includes Links
                    mayMove() {
                        return !this.diagram.isReadOnly && this.diagram.allowMove;
                    }

                    // override to move Links (which are all assumed to be MessageLinks) by
                    // updating their Link.data.time property so that their link routes will
                    // have the correct vertical position
                    moveParts(parts, offset, check) {
                        super.moveParts(parts, offset, check);
                        const it = parts.iterator;
                        while (it.next()) {
                            if (it.key instanceof go.Link) {
                                const link = it.key;
                                const startY = it.value.point.y; // DraggingInfo.point.y
                                let y = startY + offset.y; // determine new Y coordinate value for this link
                                const cellY = this.gridSnapCellSize.height;
                                y = Math.round(y / cellY) * cellY; // snap to multiple of gridSnapCellSize.height
                                const t = Math.max(0, convertYToTime(y));
                                link.diagram.model.set(link.data, "time", t);
                                link.invalidateRoute();
                            }
                        }
                    }
                }
                // end MessageDraggingTool

                //GENERAR CODIGO 
                function generateCode() {
                    const diagramJson = myDiagram.model.toJson();

                    // Envía una solicitud AJAX para generar los códigos en los tres lenguajes
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('generarcodigo') }}", // Asegúrate de que la ruta sea la misma que en web.php
                        data: {
                            _token: "{{ csrf_token() }}",
                            diagrama_json: diagramJson,
                        },
                        success: function(response) {
                            // Maneja la respuesta que contiene los códigos en los tres lenguajes
                            alert('Código Java generado:\n' + response.codigo_java);
                            alert('Código Python generado:\n' + response.codigo_python);
                            alert('Código JavaScript generado:\n' + response.codigo_javascript);
                        },
                        error: function(error) {
                            console.log(error)
                            alert('Error al generar los códigos');
                        }
                    });
                }



                // Show the diagram's model in JSON format
                //GUARDAR EL DIAGRAMA EN LA BASE DE DATOS
                var diagramId = {{ $diagramaId }};
                // console.log("diagramId: " + diagramId);
                var diagramContent = {!! json_encode($contenidoDiagrama) !!}; // Asegura que el contenido sea tratado como JSON válido

                function save() {
                    const diagramJson = myDiagram.model.toJson();
                    console.log("diagramJson: " + diagramJson);
                    // Utiliza AJAX (en este caso, con jQuery) para enviar el JSON al servidor
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('guardar-diagrama') }}",
                        data: {
                            _token: "{{ csrf_token() }}",
                            diagrama_json: diagramJson,
                            diagramaId: "{{ $diagramaId }}",
                        },
                        success: function(response) {
                            alert('Diagrama guardado con éxito');
                        },
                        error: function(error) {
                            alert('Error al guardar el diagrama');
                        }
                    });
                    myDiagram.isModified = false;
                }

                function load() {
                    myDiagram.model = go.Model.fromJson(diagramContent);
                }


                function createObject() {
                    // Creamos un nuevo objeto y su línea de vida
                    const objKey = getNextObjectKey(); // Genera una clave única para el objeto
                    const objLocation = "400 0"; // Establece la ubicación inicial del objeto
                    const objDuration = 9; // Establece la duración de la línea de vida del objeto

                    // Agregamos el objeto al modelo del diagrama
                    myDiagram.model.addNodeData({
                        key: objKey,
                        text: objKey + ": Nuevo Objeto",
                        isGroup: true,
                        loc: objLocation,
                        duration: objDuration,
                    });

                    // También agregamos su línea de vida
                    myDiagram.model.addNodeData({
                        group: objKey,
                        start: 1, // Establece el tiempo inicial
                        duration: objDuration, // Establece la duración de la línea de vida
                    });

                    // Ahora ajustamos las alturas de las líneas de vida si es necesario
                    ensureLifelineHeights();
                }

                function getNextObjectKey() {
                    // Esta función genera una clave única para cada nuevo objeto
                    const nodes = myDiagram.model.nodeDataArray;
                    let nextKey = 1;
                    while (nodes.find(node => node.key === nextKey.toString())) {
                        nextKey++;
                    }
                    return nextKey.toString();
                }


                window.addEventListener('DOMContentLoaded', init);
            </script>

            <div id="sample">
                <div id="myDiagramDiv" style="border: solid 1px black; width: 100%; height: 400px"></div>

                <div>
                    <div>
                        <button onclick="save()">Guardar</button>
                        <button onclick="createObject()">Crear objeto</button>
                        <button onclick="load()">Cargar diagrama</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- * * * * * * * * * * * * * -->
        <!--  End of GoJS sample code  -->
    </div>
</body>

</html>
