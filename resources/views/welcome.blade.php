<!DOCTYPE html>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Alpine Memory Game</title>
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.3.5/dist/alpine.min.js" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>

</head>
<body>
     <div x-data="game()" class="px-10 flex items-center justify-center min-h-screen">
         <h1 class="fixed top-0 right-0 p-10 font-bold txt-3xl">
             <span x-text="points"></span>
             <span class="text-xs">pts</span>
         </h1>
         <div class="fixed top-0 left-0 p-10 font-bold txt-3xl">
             <button
                 @click="reset()"
                 class="rounded px-5 py-1 text-white bg-blue-700">

                 Reset
             </button>

         </div>
         <div class="grid grid-cols-4 gap-10 flex-1">

             <template x-for="card in cards">
                 <div >
                    <button
                        x-show="! card.cleared === true"
                        :style="'background: ' + (card.flipped ? card.color: '#999')"
                        class="h-32 w-full"
                        :disabled="flippedCards.length === 2"
                        @click="flipCard(card)"
                    >

                    </button>
                 </div>
             </template>

         </div>


     </div>

     <div x-data="{ show: false, message: ''  }"
          class="fixed bottom-0 right-0 p-2 mr-4 mb-4 rounded text-white bg-green-500"
          x-show.transition.opacity="show"
          x-text="message"
          @flash.window="
              message = $event.detail.message;
              show = true;
              setTimeout(() => show = false, 1000 )
              "
     >
     </div>

     <script>
         function pause(ms = 750) {
             return new Promise(resolve => setTimeout(resolve, ms));
         }

         function flash(message) {
             window.dispatchEvent(new CustomEvent('flash', {
                 detail: { message }
             }));
         }

         function game() {

             return {

                 cards: [
                     { color: 'green', flipped: false, cleared: false },
                     { color: 'red', flipped: false, cleared: false },
                     { color: 'blue', flipped: false, cleared: false },
                     { color: 'yellow', flipped: false, cleared: false },
                     { color: 'green', flipped: false, cleared: false },
                     { color: 'red', flipped: false, cleared: false },
                     { color: 'blue', flipped: false, cleared: false },
                     { color: 'yellow', flipped: false, cleared: false }
                 ].sort(() => Math.random() - .5),

                 get flippedCards() {
                     return this.cards.filter(card => card.flipped);
                 },
                 get clearedCards() {
                     return this.cards.filter(card => card.cleared);
                 },
                 get points() {
                     return this.clearedCards.length;
                 },
                 get remainingCards() {
                     return this.cards.filter(card => ! card.cleared);
                 },
                 reset() {
                     this.clearedCards.forEach(card => card.cleared = false)
                     this.flippedCards.forEach(card => card.flipped = false)

                 },

                 async flipCard(card) {
                     // if(this.flippedCards.length === 2 ) {
                     //     return;
                     // }
                    card.flipped = true;

                    if (this.flippedCards.length === 2) {
                        if (this.hasMatch()) {
                            flash('You found a match!')
                            await pause();

                            this.flippedCards.forEach(card => card.cleared = true);


                            if (! this.remainingCards.length) {
                                alert('You won!')
                            }
                        }
                            await pause();
                            this.flippedCards.forEach(card => card.flipped = false);
                    }

                 },

                 hasMatch() {
                     return this.flippedCards[0]['color'] === this.flippedCards[1]['color']
                 }

             };
         }

     </script>
</body>

</html>
