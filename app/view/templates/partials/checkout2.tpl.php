<?php
declare(strict_types=1);
//$form = new \Moviao\Form\FormUtils();
//$form->debugFormPost();
//echo var_dump($_POST);
if (isset($_POST["ID"]) && is_array($_POST["ID"])) {
    //echo var_dump($_POST["ID"]);
    foreach($_POST["ID"] as $key=>$val) {
        //echo $key.'=>'. $val.  ' - ' . $_POST["TICKET_QTE"][$key] .'<br>';
    }
}
?>
<div class="container">
<div class="row">
<div class="col-md-12 padding-box">

    <div class="">
        <div class="py-5 text-center">
            <h2>xxx Titre Event xxx</h2>
        </div>

        <div class="row">
            <div class="col-md-4 order-md-2 mb-4">
                <h4 class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Your cart</span>
                    <span class="badge badge-secondary badge-pill">3</span>
                </h4>
                <ul class="list-group mb-3">
                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                        <div>
                            <h6 class="my-0">Product name</h6>
                            <small class="text-muted">Brief description</small>
                        </div>
                        <span class="text-muted">$12</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                        <div>
                            <h6 class="my-0">Second product</h6>
                            <small class="text-muted">Brief description</small>
                        </div>
                        <span class="text-muted">$8</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between lh-condensed">
                        <div>
                            <h6 class="my-0">Third item</h6>
                            <small class="text-muted">Brief description</small>
                        </div>
                        <span class="text-muted">$5</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between bg-light">
                        <div class="text-success">
                            <h6 class="my-0">Promo code</h6>
                            <small>EXAMPLECODE</small>
                        </div>
                        <span class="text-success">-$5</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>Total (USD)</span>
                        <strong>$20</strong>
                    </li>
                </ul>

                <form class="card p-2">
                    <div class="input-group">
                        <input class="form-control" placeholder="Promo code" type="text">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-secondary">Redeem</button>
                        </div>
                    </div>
                </form>
            </div>








            <div class="col-md-8 order-md-1">
                <form class="needs-validation" novalidate="">

                    <h4 class="mb-3">Acheteur du billet</h4>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="firstName">Prénom</label>
                            <input class="form-control" id="firstName" placeholder="" value="" required="" type="text">
                            <div class="invalid-feedback">
                                Valid first name is required.
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="lastName">Nom</label>
                            <input class="form-control" id="lastName" placeholder="" value="" required="" type="text">
                            <div class="invalid-feedback">
                                Valid last name is required.
                            </div>
                        </div>
                    </div>


                    <div class="mb-3">
                        <label for="email">Adresse e-mail</label>
                        <input class="form-control" id="email" placeholder="you@example.com" type="email">
                        <div class="invalid-feedback">
                            Please enter a valid email address for shipping updates.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email">Confirmer l'adresse e-mail</label>
                        <input class="form-control" id="email" placeholder="you@example.com" type="email">
                        <div class="invalid-feedback">
                            Please enter a valid email address for shipping updates.
                        </div>
                    </div>







                    <h4 class="mb-3">Type d'inscription</h4>

                    <div class="mb-3">
                        <label for="is_business">Inscription en tant que</label>
                        <select class="custom-select d-block w-100" id="is_business" required="">
                            <option value="0" selected="selected">Personne physique</option>
                            <option value="1">Entreprise</option>
                        </select>
                        <div class="invalid-feedback">
                            Please enter your shipping address.
                        </div>
                    </div>


                    <div class="mb-3">
                        <label for="address">Société / Organisation</label>
                        <input class="form-control" id="address" placeholder="1234 Main St" required="" type="text">
                        <div class="invalid-feedback">
                            Please enter your shipping address.
                        </div>
                    </div>


                    <div class="mb-3">
                        <label for="address">Adresse</label>
                        <input class="form-control" id="address" placeholder="1234 Main St" required="" type="text">
                        <div class="invalid-feedback">
                            Please enter your shipping address.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address2">Adresse 2 <span class="text-muted">(Optionel)</span></label>
                        <input class="form-control" id="address2" placeholder="Apartment or suite" type="text">
                    </div>


                    <div class="mb-3">
                        <label for="address">Numero de TVA</label>
                        <input class="form-control" id="address" placeholder="1234 Main St" required="" type="text">
                        <div class="invalid-feedback">
                            Please enter your shipping address.
                        </div>
                    </div>





                    <h4 class="mb-3">Coordonnées de facturation</h4>

                    <div class="mb-3">
                        <label for="address">Address</label>
                        <input class="form-control" id="address" placeholder="1234 Main St" required="" type="text">
                        <div class="invalid-feedback">
                            Please enter your shipping address.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address2">Adresse 2 <span class="text-muted">(Optionel)</span></label>
                        <input class="form-control" id="address2" placeholder="Apartment or suite" type="text">
                    </div>








                    <hr class="mb-4">

                    <h4 class="mb-3">Paiement</h4>

                    <div class="d-block my-3">
                        <div class="custom-control custom-radio">
                            <input id="credit" name="paymentMethod" class="custom-control-input" checked="" required="" type="radio">
                            <label class="custom-control-label" for="credit">Carte de crédit</label>
                        </div>
                        <div class="custom-control custom-radio">
                            <input id="debit" name="paymentMethod" class="custom-control-input" required="" type="radio">
                            <label class="custom-control-label" for="debit">Carte de débit</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="cc-name">Name on card</label>
                            <input class="form-control" id="cc-name" placeholder="" required="" type="text">
                            <small class="text-muted">Nom complet tel qu'affiché sur la carte</small>
                            <div class="invalid-feedback">
                                Name on card is required
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="cc-number">Numéro de Carte de Crédit</label>
                            <input class="form-control" id="cc-number" placeholder="" required="" type="text">
                            <div class="invalid-feedback">
                                Credit card number is required
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="cc-expiration">Expiration</label>
                            <input class="form-control" id="cc-expiration" placeholder="" required="" type="text">
                            <div class="invalid-feedback">
                                Expiration date required
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="cc-expiration">CVV</label>
                            <input class="form-control" id="cc-cvv" placeholder="" required="" type="text">
                            <div class="invalid-feedback">
                                Security code required
                            </div>
                        </div>
                    </div>
                    <hr class="mb-4">
                    <button class="btn btn-primary btn-lg btn-block" type="submit">Continuer à la caisse</button>
                    <br/>
                </form>
            </div>
        </div>


    </div>















</div></div></div>