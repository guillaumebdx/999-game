const blocks = document.getElementsByClassName('block');
const inputs = document.getElementsByClassName('checkBoxBlock');
let previousX = 0;
let previousY = 0;
let previousNumber = 0;

for (let i=0; i< blocks.length; i++) {
    blocks[i].addEventListener('click', (event) => {
        let currentX = parseInt(blocks[i].getAttribute('data-x'));
        let currentY = parseInt(blocks[i].getAttribute('data-y'));
        let currentNumber = parseInt(event.target.textContent);
        let blockForbidden = true;
        if (previousX === currentX) {
//            console.log('meme ligne')
            if (currentY -1 === previousY || currentY +1 === previousY) {
                blockForbidden = false;
               // console.log('block ligne autorisé')
            }
        }
        if (previousY === currentY) {
         //   console.log('même collone')
            if (currentX -1 === previousX || currentX +1 === previousX) {
               // console.log('juste dessus dessous autorisé')
                blockForbidden = false;
            }
        }

        if (previousX !== currentX && previousY !== currentY) {
        //    console.log('diag')
            if (previousX -1 === currentX && previousY -1 === currentY || previousY +1 === currentY) {
             //   console.log('diag haut autorisé')
                blockForbidden = false;
            }

            if (previousX +1 === currentX && previousY -1 === currentY || previousY +1 === currentY) {
             //   console.log('diag bas autorisé')
                blockForbidden = false;
            }
        }

        if (previousX === 0 && previousY === 0) {
            blockForbidden = false;
       //     console.log('autorisé')
        }
        if (previousNumber !== 0) {
            if (previousNumber !== currentNumber) {
                blockForbidden = true;
            //    console.log('choisir un chiffre identique')
            }
        }
        if (event.target.classList.contains('forbiddenLabel')) {
            blockForbidden = true;
        }
        if (blockForbidden) {
            event.preventDefault();
        } else {
            previousX = parseInt(blocks[i].getAttribute('data-x'));
            previousY = parseInt(blocks[i].getAttribute('data-y'));
            previousNumber = parseInt(event.target.textContent);
            event.target.classList.add('forbiddenLabel')
        }
    })
}

const resetButton = document.getElementById('raz');
if (resetButton) {
    resetButton.addEventListener('click', (e) => {
        for (let i=0; i< inputs.length; i++) {
            inputs[i].checked = false;
            blocks[i].classList.remove('forbiddenLabel')
            previousX = 0;
            previousY = 0;
            previousNumber = 0;
        }
    })
}




