document.addEventListener("DOMContentLoaded", function () {
  const carousel = document.querySelector(".carousel");
  const items = Array.from(carousel.children);
  const prevButton = document.querySelector(".carousel__nav--prev");
  const nextButton = document.querySelector(".carousel__nav--next");

  let currentIndex = 4; // Ajuster l'index de départ en fonction des éléments dupliqués

  // Dupliquer les quatre premiers et les quatre derniers éléments pour la boucle infinie
  const clonesStart = [];
  const clonesEnd = [];

  for (let i = 0; i < 4; i++) {
    const startClone = items[i].cloneNode(true);
    const endClone = items[items.length - 1 - i].cloneNode(true);

    startClone.classList.add("clone");
    endClone.classList.add("clone");

    clonesStart.push(startClone);
    clonesEnd.unshift(endClone); // Insérer au début pour conserver l'ordre des derniers éléments
  }

  clonesStart.forEach((clone) => carousel.appendChild(clone));
  clonesEnd.forEach((clone) => carousel.insertBefore(clone, carousel.firstChild));

  const totalItems = carousel.children.length;

  // Largeur totale des éléments (chaque élément doit occuper une largeur relative de 54%)
  carousel.style.width = `${totalItems * 54}%`;

  // Initialiser la position du carrousel
  function setInitialPosition() {
    carousel.style.transition = "none";
    carousel.style.transform = `translateX(-${currentIndex * (54 / totalItems)}%)`;
  }

  setInitialPosition();

  function updateCarousel() {
    carousel.style.transition = "transform 0.5s ease-in-out";
    carousel.style.transform = `translateX(-${currentIndex * (54 / totalItems)}%)`;
  }

  function resetTransition() {
    carousel.style.transition = "none";
  }

  function showNextItem() {
    currentIndex++;
    updateCarousel();

    // Vérifier si l'on atteint la fin du carrousel pour revenir au premier élément
  //   carousel.addEventListener("transitionend", function handleTransition() {
  //     if (carousel.children[currentIndex].classList.contains("clone")) {
  //       resetTransition();
  //       currentIndex = 4; // Revenir au premier vrai élément (après les clones de début)
  //       carousel.style.transform = `translateX(-${currentIndex * (54 / totalItems)}%)`;
  //     }
  //     carousel.removeEventListener("transitionend", handleTransition);
  //   });
  // }

  function showPrevItem() {
    currentIndex--;
    updateCarousel();

    // Vérifier si l'on atteint le début du carrousel pour revenir au dernier élément
    carousel.addEventListener("transitionend", function handleTransition() {
      if (carousel.children[currentIndex].classList.contains("clone")) {
        resetTransition();
        currentIndex = totalItems - 5; // Revenir au dernier vrai élément (avant les clones de fin)
        carousel.style.transform = `translateX(-${currentIndex * (54 / totalItems)}%)`;
      }
      carousel.removeEventListener("transitionend", handleTransition);
    });
  }

  // Gérer le défilement automatique
  let autoScrollInterval = setInterval(showNextItem, 5000);

  function resetAutoScroll() {
    clearInterval(autoScrollInterval);
    autoScrollInterval = setInterval(showNextItem, 5000);
  }

  nextButton.addEventListener("click", () => {
    showNextItem();
    resetAutoScroll(); // Réinitialiser le défilement après un clic
  });

  prevButton.addEventListener("click", () => {
    showPrevItem();
    resetAutoScroll(); // Réinitialiser le défilement après un clic
  });

  // Réinitialiser la position lorsque la transition est terminée
  carousel.addEventListener("transitionend", () => {
    if (carousel.children[currentIndex].classList.contains("clone")) {
      resetTransition();
      if (currentIndex === 0) {
        currentIndex = totalItems - 5; // Revenir au dernier vrai élément
      } else if (currentIndex === totalItems - 1) {
        currentIndex = 4; // Revenir au premier vrai élément
      }
      carousel.style.transform = `translateX(-${currentIndex * (54 / totalItems)}%)`;
    }
  });

  // Initialiser la position pour éviter un blanc au début
  resetTransition();
  setInitialPosition();
});
