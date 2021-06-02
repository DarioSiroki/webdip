class Paginator {
  constructor(list, itemCount = 5) {
    this.setList(list, itemCount);
  }

  setList(list, itemCount = 5) {
    this.list = list.slice(0, itemCount);
    this.originalList = list;
    this.page = 1;
    this.itemCount = itemCount;
    this.max = list.length;
    this.filter = null;
  }

  setFilter(fn) {
    this.filter = fn;
    this.firstPage();
  }

  getList() {
    return this.list;
  }

  setItemCount(itemCount) {
    this.itemCount = itemCount;
    this.firstPage();
  }

  firstPage() {
    if (this.filter) {
      this.list = this.filter(this.originalList);
    } else {
      this.list = this.originalList;
    }
    this.list = this.list.slice(0, this.itemCount);
    this.page = 1;
    return this.list;
  }

  lastPage() {
    if (this.filter) {
      this.list = this.filter(this.originalList);
    } else {
      this.list = this.originalList;
    }
    let offset = this.list.length - this.itemCount;
    if (offset < 0) offset = 0;
    this.list = this.list.slice(offset, offset + this.itemCount);
    this.page = Math.ceil(this.originalList.length / this.itemCount);
    return this.list;
  }

  nextPage() {
    this.page++;
    if (this.filter) {
      this.list = this.filter(this.originalList);
    } else {
      this.list = this.originalList;
    }
    let offset = (this.page - 1) * this.itemCount;
    if (offset < 0) offset = 0;
    this.list = this.list.slice(offset, offset + this.itemCount);
    return this.list;
  }

  prevPage() {
    this.page--;
    if (this.filter) {
      this.list = this.filter(this.originalList);
    } else {
      this.list = this.originalList;
    }
    let offset = (this.page - 1) * this.itemCount;
    if (offset < 0) offset = 0;
    this.list = this.list.slice(offset, offset + this.itemCount);
    return this.list;
  }
}
