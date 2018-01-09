<nav class="navbar navbar-default sidebar" style="border: 1px solid #f1f1f1;" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header" style="background-color: #e1e1e1;">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-sidebar-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>      
        </div>
        <div class="collapse navbar-collapse"  id="bs-sidebar-navbar-collapse-1">
            <ul class="nav navbar-nav" style="margin-left: -14px;">
                <li>
                    <a style="background-color:{{bgcolor}}; color: #fff; margin-left: -14px;" href="estore/{{id_space}}"> {{title}} <span style="font-size:16px;" class="pull-right hidden-xs showopacity glyphicon {{glyphicon}}"></span></a>
                </li>
                <ul class="pm-nav-li">
                    <!--
                    <li>
                        {{Sales}}
                    </li>
                    -->
                    <li>
                        <div class="inline pm-inline-div">
                            
                                {{countEntered}}
                            
                            <a id="menu-button" href="essaleentered/{{id_space}}">{{SalesEntered}}</a> 
                            
                        </div>
                    </li>
                    <li>
                        <div class="inline pm-inline-div">
                            {{countInProgress}}
                            
                            <a id="menu-button" href="esalesinprogress/{{id_space}}">{{SalesInProgress}}</a> 
                        </div>
                    </li>
                    <li>
                        <div class="inline pm-inline-div">
                            {{countQuoted}}
                            
                            <a id="menu-button" href="esalesquoted/{{id_space}}">{{SalesQuoted}}</a> 
                        </div>
                    </li>
                    
                    <li>
                        <div class="inline pm-inline-div">
                            {{countSent}}
                            <a id="menu-button" href="esalessent/{{id_space}}">{{SalesSent}}</a> 
                        </div>
                    </li>
                    <li>
                        <div class="inline pm-inline-div">
                            <div class="pm-space-menu-notification"></div>
                            <a id="menu-button" href="esalescanceled/{{id_space}}">{{SalesCanceled}}</a> 
                        </div>
                    </li>
                    <li>
                        <div class="inline pm-inline-div">
                            <div class="pm-space-menu-notification"></div>
                            <a id="menu-button" href="esalesarchive/{{id_space}}">{{SalesArchives}}</a> 
                        </div>
                    </li>
                    <br/>
                    <li>
                        {{Products}}
                    </li>
                    <li>
                        <div class="inline pm-inline-div">
                            <a id="menu-button" href="esproductcategories/{{id_space}}">{{CategoriesProduct}}</a> 
                        </div>
                    </li>
                    <li>
                        <div class="inline pm-inline-div">
                            <a id="menu-button" href="esproducts/{{id_space}}">{{Products}}</a> 
                        </div>
                    </li>
                    <li>
                        <div class="inline pm-inline-div">
                            <a id="menu-button" href="esprices/{{id_space}}">{{Prices}}</a> 
                        </div>
                    </li>
                    
                    <br/>
                    
                    <li>
                        {{OtherInfo}}
                    </li>
                    <li>
                        <div class="inline pm-inline-div">
                            <a id="menu-button" href="escontacttypes/{{id_space}}">{{ContactTypes}}</a>
                        </div>
                    </li>
                    <li>
                        <div class="inline pm-inline-div">
                            <a id="menu-button" href="esdeliveries/{{id_space}}">{{Delivery}}</a>
                        </div>
                    </li>
                    
                </ul>
            </ul>
        </div>
    </div>
</nav>

