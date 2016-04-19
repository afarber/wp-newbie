jQuery(document).ready(function($) {

        var board = document.getElementById("board");
        var ctx = board.getContext("2d");
        ctx.strokeStyle = "lightgray";

        var canvasOffset = $("#board").offset();
        var offsetX = canvasOffset.left;
        var offsetY = canvasOffset.top;
        
        var scrolltop = $(document).scrollTop();
        var scrollleft = $(document).scrollLeft();
        
        console.log('ready: offsetX=' + offsetX + ", " + "offsetY=" + offsetY);

        var mouseIsDown = false;
        var lastX = 0;
        var lastY = 0;

        var ships = [];

        makeShip(20, 30, 50, 25, "skyblue");
        makeShip(20, 100, 30, 25, "skyblue");
        makeShip(20, 170, 50, 25, "salmon");
        makeShip(20, 240, 30, 25, "salmon");

        function makeShip(x, y, width, height, fill) {
            var ship = {
                x: x,
                y: y,
                width: width,
                height: height,
                right: x + width,
                bottom: y + height,
                fill: fill
            }
            ships.push(ship);
            return (ship);
        }

        drawAllShips();

        function drawAllShips() {
            ctx.clearRect(0, 0, board.width, board.height);
            for (var i = 0; i < ships.length; i++) {
                var ship = ships[i]
                drawShip(ship);
                ctx.fillStyle = ship.fill;
                ctx.fill();
                ctx.stroke();
            }
        }

        function drawShip(ship) {
            ctx.beginPath();
            ctx.moveTo(ship.x, ship.y);
            ctx.lineTo(ship.right, ship.y);
            ctx.lineTo(ship.right + 10, ship.y + ship.height / 2);
            ctx.lineTo(ship.right, ship.bottom);
            ctx.lineTo(ship.x, ship.bottom);
            ctx.closePath();
        }

        function handleMouseDown(e) {
            scrolltop = $(document).scrollTop();
            scrollleft = $(document).scrollLeft();

            mouseX = parseInt(e.clientX - offsetX + scrollleft);
            mouseY = parseInt(e.clientY - offsetY + scrolltop);

            lastX = mouseX;
            lastY = mouseY;
            mouseIsDown = true;

            console.log('handleMouseDown: mouseX=' + mouseX + ", " + "mouseY=" + mouseY);
        }

        function handleMouseUp(e) {
            mouseX = parseInt(e.clientX - offsetX + scrollleft);
            mouseY = parseInt(e.clientY - offsetY + scrolltop);

            console.log('handleMouseUp: mouseX=' + mouseX + ", " + "mouseY=" + mouseY);

            mouseIsDown = false;
        }

        function handleMouseMove(e) {
            if (!mouseIsDown) {
                return;
            }
                                                
            mouseX = parseInt(e.clientX - offsetX + scrollleft);
            mouseY = parseInt(e.clientY - offsetY + scrolltop);

            console.log('handleMouseMove: mouseX=' + mouseX + ", " + "mouseY=" + mouseY);

            for (var i = 0; i < ships.length; i++) {
                var ship = ships[i];
                drawShip(ship);
                if (ctx.isPointInPath(lastX, lastY)) {
                    ship.x += (mouseX - lastX);
                    ship.y += (mouseY - lastY);
                    ship.right = ship.x + ship.width;
                    ship.bottom = ship.y + ship.height;
                }
            }

            lastX = mouseX;
            lastY = mouseY;
            drawAllShips();
        }

        $("#board").mousedown(function (e) {
            handleMouseDown(e);
        });

        $("#board").mousemove(function (e) {
            handleMouseMove(e);
        });

        $("#board").mouseup(function (e) {
            handleMouseUp(e);
        });

});

