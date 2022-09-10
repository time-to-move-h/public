<div id="tpl_tickets" style="display: none">
<div class="form-group row">

    <label class="col-12">
        <p><strong>{{=it.name}}</strong></p>

        <div class="form-group row">
            <label class="col">
                <div>{{=it.price}} €</div>
            </label>
            <div class="col">
                <div>
                    <input type="hidden" name="ID[]" value="{{=it.id}}">
                    <select id="TICKET_QTE_{{=it.id}}" name="{{=it.select_name}}[]" data-ticket-price="{{=it.price}}">
                        {{=it.maxqte}}
                    </select>
                </div>
            </div>
        </div>

<!--        <div class="row">-->
<!--            <div class="col-3">-->
<!--                <p>{{=it.price}} €</p>-->
<!--            </div>-->
<!--            <div class="col-3 ml-auto">-->
<!--                <input type="hidden" name="ID[]" value="{{=it.id}}">-->
<!--                <select id="TICKET_QTE_{{=it.id}}" class="custom-select d-block w-100" name="{{=it.select_name}}[]" data-ticket-price="{{=it.price}}">-->
<!--                    {{=it.maxqte}}-->
<!--                </select>-->
<!--            </div>-->
<!--        </div>-->

    </label>

</div>
</div>