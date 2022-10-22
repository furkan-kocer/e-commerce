jQuery(".event-row").each(function(){
    const $this =jQuery(this);
    const id=$this.attr("data-id");
    $this.find("div:contains(EXPAND)").click(function(){
        jQuery(".list[data-id="+id+"]").toggle("show");
    });
});