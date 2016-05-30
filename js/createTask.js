/**
 * Created by Dragos on 08/03/2016.
 */

//créer GRAPH
var graph = new joint.dia.Graph;

//créer PAPER
//le PAPER prend les valeurs du width/height de la section
var w = $('section')[0].offsetWidth;
var h = $('section')[0].offsetHeight;
var paper = new joint.dia.Paper({
    el: $('section'),
    width: w,
    height: h,
    model: graph,
    gridSize: 1
});

/*  BOUNDS
    On veut que les figures ne dépassent pas les limites de la section.
    On crée un Rect non-interactif qui va contenir tous les figures.
    Ce Rect est insensible à la souris et sa taille est w et h.
 */
var bounds = new joint.shapes.basic.Rect({
        size: {width: paper.options.width, height: paper.options.height},
        attrs: {rect: {style: {'pointer-events': 'none'}, 'stroke-width': 0, fill: '#fff', 'fill-opacity': 0.1 }}
    });
graph.addCell(bounds);
graph.on('change:position', function(cell) {
    var parent = graph.getCell(bounds.id);
    var parentBbox = parent.getBBox();
    var cellBbox = cell.getBBox();

    if (parentBbox.containsPoint(cellBbox.origin()) &&
        parentBbox.containsPoint(cellBbox.topRight()) &&
        parentBbox.containsPoint(cellBbox.corner()) &&
        parentBbox.containsPoint(cellBbox.bottomLeft())) {
        return;
    }

    cell.set('position', cell.previous('position'));
    });

function autoResizeBounds() {
    w = $('section')[0].offsetWidth;
    h = $('section')[0].offsetHeight;
    paper.setDimensions(w,h);
    paper.scaleContentToFit(); //à reconnaitre quand c'est full page !!!
};

/*************************
    INIT DEBUT ---> FIN
 *************************/
//créer les cercles de DEBUT et FIN
var extreme = new joint.shapes.basic.Circle( {
    size: { width:100, height: 100},
    attrs: {
        circle: { fill: '#FFFFFF', 'fill-opacity': 0.5,  'stroke-width': 0 },
        text: {
            fill: '#000', 'fill-opacity':0.7, 'font-size': 18, 'font-weight': 'bold', 'font-variant': 'small-caps', 'text-transform': 'capitalize'
        }
    }
});
bounds.embed(extreme);

/*************************
        LIENS
 *************************/
//création de la petite flêche noire au bout du lien
var fleche = {'.connection': { stroke: '#000', 'stroke-opacity': 0.7 }, '.marker-target': { fill: '#000', 'fill-opacity':0.7, stroke: 0, d: 'M 10 0 L 0 5 L 10 10 z' }};
//creation du lien
var lien = new joint.dia.Link({ attrs: fleche, smooth: true});


/*************************
        TACHES
 *************************/
//créer le rectangle correspondant à la tâche
var rect = new joint.shapes.basic.Rect({
    size: { width: 100, height: 60 },
    attrs: {
        rect: { fill: '#3498DB', 'fill-opacity': 0.7, rx: 5, ry: 5, stroke: 0 },
        text: {
            text: 'my label', fill: '#000', 'fill-opacity':0.7,
            'font-size': 18, 'font-weight': 'bold', 'font-variant': 'small-caps', 'text-transform': 'capitalize'
        }
    }
});
bounds.embed(rect);

//fonction qui permet de récupérer une cellule par son texte
function getCellByText(str) {
    var i = 0;
    while (i < graph.getCells().length) {
       if (graph.getCells()[i].attr('text/text') === str) {
          return graph.getCells()[i];
       };
       i++;
    }
    return null;
};

//fonction qui permet de récupérer tous les liens d'une même source
function getLinksFromSource(source) {
    var outputLinks = [];

    graph.getLinks().forEach(function (link) {
        if (link.getSourceElement() === source) {
            outputLinks.push(link);
        }
    });

    return outputLinks;
};

function createLink() {
    var left = getCellByText($('#sourceSelector').val());
    var right = getCellByText($('#targetSelector').val());
    var link = new joint.dia.Link({
        source: { id: left.id},
        target: { id: right.id},
        attrs: fleche
    });
    graph.addCell(link);
    $('#linkConfig').slideToggle();
};

function reorganizeGraphPositions() {
    var start = getCellByText("Start");
    var queue = [];
    var listElements = [];
    var qtdElements = [];
    var level = 0;

    queue.push({element : start, level : level});
    // qtdElements.push({level : 0, qtd : 1});

    while(queue.length > 0) {
        var current = queue.shift();
        var links = getLinksFromSource(current.element);
        for (var i = 0; i < links.length; i++) {
            console.log(links[i].getTargetElement() !== getCellByText("End"));
            if (links[i].getTargetElement() !== getCellByText("End")) {
                queue.push({element: links[i].getTargetElement(), level: current.level + 1});
            }

            var isElementFound = false;
            for (var j = 0; j < listElements.length && !isElementFound; j++) {
                //console.log(listElements[j].element === links[i].getTargetElement());
                //console.log(listElements[j].level < current.level + 1);
                if(links[i].getTargetElement() !== getCellByText("End")
                    && listElements[j].element === links[i].getTargetElement()
                    && listElements[j].level <= current.level + 1)
                {
                    listElements[j].level = current.level + 1;
                    isElementFound = true;

                    // isElementFoundQtd = false;
                    // for (var j = 0; j < qtdElements.length && !isElementFoundQtd; j++) {
                    //     if(qtdElements[j].level === current.level + 1) {
                    //         qtdElements[j].qtd = qtdElements[j].qtd + 1;
                    //         isElementFoundQtd = true;
                    //     }
                    // }
                    // if(!isElementFoundQtd) {
                    //     qtdElements.push({level: current.level + 1, qtd : 1});
                    // }
                }
            }
            if(!isElementFound && links[i].getTargetElement() !== getCellByText("End")) {
                listElements.push({element : links[i].getTargetElement(), level : current.level + 1});
                // qtdElements.push({level: current.level + 1, qtd : 1});

                isElementFoundQtd = false;
                for (var j = 0; j < qtdElements.length && !isElementFoundQtd; j++) {
                    if(qtdElements[j].level === current.level + 1) {
                        qtdElements[j].qtd = qtdElements[j].qtd + 1;
                        isElementFoundQtd = true;
                    }
                }
                if(!isElementFoundQtd) {
                    qtdElements.push({level: current.level + 1, qtd : 1});
                }
            }

            // isElementFound = false;
            // for (var j = 0; j < qtdElements.length && !isElementFound; j++) {
            //     if(qtdElements[j].level === current.level + 1) {
            //         qtdElements[j].qtd = qtdElements[j].qtd + 1;
            //         isElementFound = true;
            //     }
            // }
            // if(!isElementFound) {
            //     qtdElements.push({level: current.level + 1, qtd : 1});
            // }
        };
    }

    var longestPath = calculateLongestPath(start);

    // console.log("listElements " + listElements);
    // alert(JSON.stringify(listElements, null, 4));
    // alert(JSON.stringify(qtdElements, null, 4));

    updateElementsPositions(listElements, qtdElements, longestPath);

    return longestPath;
};

function updateElementsPositions(listElements, qtdElements, longestPath) {
    qtdElements.sort(function (a, b) {
        if (a.level > b.level) {
            return 1;
        }
        if (a.value < b.value) {
            return -1;
        }
        // a must be equal to b
        return 0;
    });

    // for (var i = 0; i < listElements.length; i++) {
    //   listElements[i]
    // }
    //
    // isElementFound = false;
    // for (var j = 0; j < qtdElements.length && !isElementFound; j++) {
    //     if(qtdElements[j].level === current.level + 1) {
    //         qtdElements[j].qtd = qtdElements[j].qtd + 1;
    //         isElementFound = true;
    //     }
    // }
    // if(!isElementFound) {
    //     qtdElements.push({level: current.level + 1, qtd : 1});
    // }

    //start.position(10, bounds.get('size').height/2-50);
    //end.position(bounds.get('size').width-200, bounds.get('size').height/2-50);
    var s = getCellByText("Start");
    var e = getCellByText("End");
    var xDistBetweenElements = (e.get('position').x - s.get('position').x)/longestPath;
    var yDistBetweenElements = 150;
    var elementHeight = 60;
    var centralLineY = s.get('position').y + s.get('size').height/2;

    // alert(JSON.stringify(qtdElements, null, 4));
    for (var i = 0; i < qtdElements.length; i++) {
        var level = qtdElements[i].level;
        var qtd = qtdElements[i].qtd;
        var sameLevelElements = findAllElementsByLevel(listElements, level);
        // var qtd = sameLevelElements.length;
        var yInitialPosition = centralLineY - (elementHeight)/2 - ((qtd-1)*yDistBetweenElements/2);
        // alert(centralLineY);
        // var yInitialPosition = centralLineY - elementHeight/2 - ((qtd-1)/2)*(yDistBetweenElements);
        // alert(s.get('position').y);
        // alert(yInitialPosition);
        for (var j = 0; j < sameLevelElements.length; j++) {

          var element = getCellByText(sameLevelElements[j].element.attr('text/text'));

          if(qtd % 2 === 0) {
            element.set('position', {x: xDistBetweenElements*level, y: (yInitialPosition + j*yDistBetweenElements)});//(3*j/2)*(yDistBetweenElements - elementHeight)*2)}); // - elementHeight/2
            // alert(yInitialPosition + (3*j/2)*yDistBetweenElements - elementHeight/2);
          } else {
            element.set('position', {x: xDistBetweenElements*level, y: (yInitialPosition + j*yDistBetweenElements)});
            // alert(yInitialPosition);
            // alert(j*yDistBetweenElements);
          }
          //taskRect.set('position', {x: 0, y: 0});
          //console.log("nom element " + e.element.attr('text/text'));
          //element.set('position', {x: (xDistBetweenElements*(level+1) + s.get('position').x), y: (yInitialPosition + (3*level/2)*yDistBetweenElements)});

        }
    };
};

function findAllElementsByLevel(list, level) {
    var elements = [];
    for (var i = 0; i < list.length; i++) {
        if(list[i].level === level) {
            elements.push(list[i]);
        }
    }
    //return e.level == level;
    return elements;
}

function calculateLongestPath(element) {
    var max = 0;
    var dist = 0;
    if(element.attr('text/text') !== "End") {
        var links = getLinksFromSource(element);
        for (var i = 0; i < links.length; i++) {
            dist = 1 + calculateLongestPath(links[i].getTargetElement());
            if(dist > max) {
                max = dist;
            }
        };
    }
    return max;
}

/*function getLevel(elements, level) {
 var result  = elements.filter(
 function(o) {
 return o.level == level;
 }
 );

 return result? result[0] : null; // or undefined
 };*/

/*function existLevel(array, level) {
 return array.some(function(arrLevel) {
 return level === arrLevel.level;
 });
 };*/

$('')
