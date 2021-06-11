class PageTour {
  constructor(config) {
    this.config = config;
    this.currentlyActive = -1;
    this.prevBox = null;
  }

  start() {
    this.currentlyActive = -1;
    this.next();
  }

  isEndOfTour() {
    return this.currentlyActive === this.config.length - 1;
  }

  next() {
    // Makni prethodnu poruku ako postoji
    if (this.prevBox != null) {
      this.prevBox.remove();
    }
    if (this.isEndOfTour()) {
      return;
    }
    const currentConfig = this.config[++this.currentlyActive];
    const { targetId, description } = currentConfig;

    const target = document.getElementById(targetId);
    const { offsetTop, offsetLeft } = target;
    const div = document.createElement("div");
    const p = document.createElement("p");
    const btn = document.createElement("button");

    div.appendChild(p);
    div.appendChild(btn);

    p.innerHTML = description;
    btn.innerHTML = "Dalje";
    btn.onclick = () => this.next();

    div.className = "tour-box";
    div.style.left = offsetLeft + "px";
    div.style.top = offsetTop + "px";

    document.body.appendChild(div);
    this.prevBox = div;
    window.scrollTo(offsetLeft, offsetTop);
  }
}
