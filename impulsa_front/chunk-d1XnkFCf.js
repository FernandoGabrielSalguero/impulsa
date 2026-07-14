import {T,aO as Dr,aY as x,d8 as We,bG as j$1,by as Ah,b$ as tn,a as TE,aS as j0,aT as gD,aW as mD,ac as Qp,as as RD,f as fh,aC as bE,aD as cu,ck as ca,ci as jo,bH as ri,bh as Hl,bM as at$1,a$ as fy,aZ as SE,a_ as Hp,aJ as JD,bp as ih,aM as vD,aN as ID,bC as Ai,bf as Kr,bO as or,aH as Se,aP as MI,aE as $e,be as ne,bb as ct$1,bj as en,bD as Re,bJ as aa,aI as zm,b as yi,$ as $p,j as jc,W as Wv,c as Zp,aL as sh,bv as hn,b9 as L0,bw as ne$1,bx as Js,cn as Dt,bl as kg,bN as In,aU as Vc,aV as Hc,Q as QE,Z as ZE,bz as V0,n as nh,am as dh,aX as Er,d9 as Tr,da as oi,ai as rg,aj as Ul,a7 as Ce,db as ug,ak as Ki,ce as bg,dc as Vl,dd as es,d4 as Me,o as an,a8 as ze,s as re$1,V as Vo,bS as Gu,cd as Kp,cy as oe,cK as _,de as Hg,bk as Bl,bF as hr}from'./main-JMCB4VHO.js';var j=(()=>{class i{_listeners=[];notify(e,t){for(let n of this._listeners)n(e,t);}listen(e){return this._listeners.push(e),()=>{this._listeners=this._listeners.filter(t=>e!==t);}}ngOnDestroy(){this._listeners=[];}static \u0275fac=function(t){return new(t||i)};static \u0275prov=Er({token:i,factory:i.\u0275fac})}return i})();var re=new x("CdkAccordion"),Ke=(()=>{class i{_stateChanges=new ne;_openCloseAllActions=new ne;id=T(ct$1).getId("cdk-accordion-");multi=false;openAll(){this.multi&&this._openCloseAllActions.next(true);}closeAll(){this._openCloseAllActions.next(false);}ngOnChanges(e){this._stateChanges.next(e);}ngOnDestroy(){this._stateChanges.complete(),this._openCloseAllActions.complete();}static \u0275fac=function(t){return new(t||i)};static \u0275dir=SE({type:i,selectors:[["cdk-accordion"],["","cdkAccordion",""]],inputs:{multi:[2,"multi","multi",j0]},exportAs:["cdkAccordion"],features:[JD([{provide:re,useExisting:i}]),zm]})}return i})(),Ye=(()=>{class i{accordion=T(re,{optional:true,skipSelf:true});_changeDetectorRef=T(L0);_expansionDispatcher=T(j);_openCloseAllSubscription=j$1.EMPTY;closed=new $e;opened=new $e;destroyed=new $e;expandedChange=new $e;id=T(ct$1).getId("cdk-accordion-child-");get expanded(){return this._expanded}set expanded(e){if(this._expanded!==e){if(this._expanded=e,this.expandedChange.emit(e),e){this.opened.emit();let t=this.accordion?this.accordion.id:this.id;this._expansionDispatcher.notify(this.id,t);}else this.closed.emit();this._changeDetectorRef.markForCheck();}}_expanded=false;get disabled(){return this._disabled()}set disabled(e){this._disabled.set(e);}_disabled=Vo(false);_removeUniqueSelectionListener=()=>{};ngOnInit(){this._removeUniqueSelectionListener=this._expansionDispatcher.listen((e,t)=>{this.accordion&&!this.accordion.multi&&this.accordion.id===t&&this.id!==e&&(this.expanded=false);}),this.accordion&&(this._openCloseAllSubscription=this._subscribeToOpenCloseAllActions());}ngOnDestroy(){this.opened.complete(),this.closed.complete(),this.destroyed.emit(),this.destroyed.complete(),this._removeUniqueSelectionListener(),this._openCloseAllSubscription.unsubscribe();}toggle(){this.disabled||(this.expanded=!this.expanded);}close(){this.disabled||(this.expanded=false);}open(){this.disabled||(this.expanded=true);}_subscribeToOpenCloseAllActions(){return this.accordion._openCloseAllActions.subscribe(e=>{this.disabled||(this.expanded=e);})}static \u0275fac=function(t){return new(t||i)};static \u0275dir=SE({type:i,selectors:[["cdk-accordion-item"],["","cdkAccordionItem",""]],inputs:{expanded:[2,"expanded","expanded",j0],disabled:[2,"disabled","disabled",j0]},outputs:{closed:"closed",opened:"opened",destroyed:"destroyed",expandedChange:"expandedChange"},exportAs:["cdkAccordionItem"],features:[JD([{provide:re,useValue:void 0}])]})}return i})(),Ge=(()=>{class i{static \u0275fac=function(t){return new(t||i)};static \u0275mod=bE({type:i});static \u0275inj=cu({})}return i})();var se=class{_multiple;_emitChanges;compareWith;_selection=new Set;_deselectedToEmit=[];_selectedToEmit=[];_selected=null;get selected(){return this._selected||(this._selected=Array.from(this._selection.values())),this._selected}changed=new ne;bulk={select:a=>this._select(a),deselect:a=>this._deselect(a),setSelection:a=>this._setSelection(a)};constructor(a=false,e,t=true,n){this._multiple=a,this._emitChanges=t,this.compareWith=n,e&&e.length&&(a?e.forEach(o=>this._markSelected(o)):this._markSelected(e[0]),this._selectedToEmit.length=0);}select(...a){return this._select(a)}deselect(...a){return this._deselect(a)}setSelection(...a){return this._setSelection(a)}toggle(a){return this.isSelected(a)?this.deselect(a):this.select(a)}clear(a=true){this._unmarkAll();let e=this._hasQueuedChanges();return a&&this._emitChangeEvent(),e}isSelected(a){return this._selection.has(this._getConcreteValue(a))}isEmpty(){return this._selection.size===0}hasValue(){return !this.isEmpty()}sort(a){this._multiple&&this.selected&&this._selected.sort(a);}isMultipleSelection(){return this._multiple}_select(a){this._verifyValueAssignment(a),a.forEach(t=>this._markSelected(t));let e=this._hasQueuedChanges();return this._emitChangeEvent(),e}_deselect(a){this._verifyValueAssignment(a),a.forEach(t=>this._unmarkSelected(t));let e=this._hasQueuedChanges();return this._emitChangeEvent(),e}_setSelection(a){this._verifyValueAssignment(a);let e=this.selected,t=new Set(a.map(o=>this._getConcreteValue(o)));a.forEach(o=>this._markSelected(o)),e.filter(o=>!t.has(this._getConcreteValue(o,t))).forEach(o=>this._unmarkSelected(o));let n=this._hasQueuedChanges();return this._emitChangeEvent(),n}_emitChangeEvent(){this._selected=null,(this._selectedToEmit.length||this._deselectedToEmit.length)&&(this.changed.next({source:this,added:this._selectedToEmit,removed:this._deselectedToEmit}),this._deselectedToEmit=[],this._selectedToEmit=[]);}_markSelected(a){a=this._getConcreteValue(a),this.isSelected(a)||(this._multiple||this._unmarkAll(),this.isSelected(a)||this._selection.add(a),this._emitChanges&&this._selectedToEmit.push(a));}_unmarkSelected(a){a=this._getConcreteValue(a),this.isSelected(a)&&(this._selection.delete(a),this._emitChanges&&this._deselectedToEmit.push(a));}_unmarkAll(){this.isEmpty()||this._selection.forEach(a=>this._unmarkSelected(a));}_verifyValueAssignment(a){a.length>1&&this._multiple;}_hasQueuedChanges(){return !!(this._deselectedToEmit.length||this._selectedToEmit.length)}_getConcreteValue(a,e){if(this.compareWith){e=e??this._selection;for(let t of e)if(this.compareWith(a,t))return t;return a}else return a}};var Xe=class{applyChanges(a,e,t,n,o){a.forEachOperation((r,l,d)=>{let c,h;if(r.previousIndex==null){let J=t(r,l,d);c=e.createEmbeddedView(J.templateRef,J.context,J.index),h=oe.INSERTED;}else d==null?(e.remove(l),h=oe.REMOVED):(c=e.get(l),e.move(c,d),h=oe.MOVED);o&&o({context:c?.context,operation:h,record:r});});}detach(){}};var ct=["body"],dt=["bodyWrapper"],pt=[[["mat-expansion-panel-header"]],"*",[["mat-action-row"]]],ht=["mat-expansion-panel-header","*","mat-action-row"];function mt(i,a){}var ut=[[["mat-panel-title"]],[["mat-panel-description"]],"*"],gt=["mat-panel-title","mat-panel-description","*"];function ft(i,a){i&1&&(Vc(0,"span",1),Gu(),Vc(1,"svg",2),Kp(2,"path",3),Hc()());}var le=new x("MAT_ACCORDION"),Je=new x("MAT_EXPANSION_PANEL"),_t=(()=>{class i{_template=T(hr);_expansionPanel=T(Je,{optional:true});static \u0275fac=function(t){return new(t||i)};static \u0275dir=SE({type:i,selectors:[["ng-template","matExpansionPanelContent",""]]})}return i})(),et=new x("MAT_EXPANSION_PANEL_DEFAULT_OPTIONS"),xt=(()=>{class i extends Ye{_viewContainerRef=T(Ai);_animationsDisabled=Kr();_document=T(or);_ngZone=T(Se);_elementRef=T(Dr);_renderer=T(MI);_cleanupTransitionEnd;get hideToggle(){return this._hideToggle||this.accordion&&this.accordion.hideToggle}set hideToggle(e){this._hideToggle=e;}_hideToggle=false;get togglePosition(){return this._togglePosition||this.accordion&&this.accordion.togglePosition}set togglePosition(e){this._togglePosition=e;}_togglePosition;afterExpand=new $e;afterCollapse=new $e;_inputChanges=new ne;accordion=T(le,{optional:true,skipSelf:true});_lazyContent;_body;_bodyWrapper;_portal;_headerId=T(ct$1).getId("mat-expansion-panel-header-");constructor(){super();let e=T(et,{optional:true});this._expansionDispatcher=T(j),e&&(this.hideToggle=e.hideToggle);}_hasSpacing(){return this.accordion?this.expanded&&this.accordion.displayMode==="default":false}_getExpandedState(){return this.expanded?"expanded":"collapsed"}toggle(){this.expanded=!this.expanded;}close(){this.expanded=false;}open(){this.expanded=true;}ngAfterContentInit(){this._lazyContent&&this._lazyContent._expansionPanel===this&&this.opened.pipe(Hl(null),en(()=>this.expanded&&!this._portal),tn(1)).subscribe(()=>{this._portal=new Re(this._lazyContent._template,this._viewContainerRef);}),this._setupAnimationEvents();}ngOnChanges(e){this._inputChanges.next(e);}ngOnDestroy(){super.ngOnDestroy(),this._cleanupTransitionEnd?.(),this._inputChanges.complete();}_containsFocus(){if(this._body){let e=this._document.activeElement,t=this._body.nativeElement;return e===t||t.contains(e)}return  false}_transitionEndListener=({target:e,propertyName:t})=>{e===this._bodyWrapper?.nativeElement&&t==="grid-template-rows"&&this._ngZone.run(()=>{this.expanded?this.afterExpand.emit():this.afterCollapse.emit();});};_setupAnimationEvents(){this._ngZone.runOutsideAngular(()=>{this._animationsDisabled?(this.opened.subscribe(()=>this._ngZone.run(()=>this.afterExpand.emit())),this.closed.subscribe(()=>this._ngZone.run(()=>this.afterCollapse.emit()))):setTimeout(()=>{let e=this._elementRef.nativeElement;this._cleanupTransitionEnd=this._renderer.listen(e,"transitionend",this._transitionEndListener),e.classList.add("mat-expansion-panel-animations-enabled");},200);});}static \u0275fac=function(t){return new(t||i)};static \u0275cmp=TE({type:i,selectors:[["mat-expansion-panel"]],contentQueries:function(t,n,o){if(t&1&&ih(o,_t,5),t&2){let r;vD(r=ID())&&(n._lazyContent=r.first);}},viewQuery:function(t,n){if(t&1&&sh(ct,5)(dt,5),t&2){let o;vD(o=ID())&&(n._body=o.first),vD(o=ID())&&(n._bodyWrapper=o.first);}},hostAttrs:[1,"mat-expansion-panel"],hostVars:4,hostBindings:function(t,n){t&2&&fh("mat-expanded",n.expanded)("mat-expansion-panel-spacing",n._hasSpacing());},inputs:{hideToggle:[2,"hideToggle","hideToggle",j0],togglePosition:"togglePosition"},outputs:{afterExpand:"afterExpand",afterCollapse:"afterCollapse"},exportAs:["matExpansionPanel"],features:[JD([{provide:le,useValue:void 0},{provide:Je,useExisting:i}]),Hp,zm],ngContentSelectors:ht,decls:9,vars:4,consts:[["bodyWrapper",""],["body",""],[1,"mat-expansion-panel-content-wrapper"],["role","region",1,"mat-expansion-panel-content",3,"id"],[1,"mat-expansion-panel-body"],[3,"cdkPortalOutlet"]],template:function(t,n){t&1&&(gD(pt),mD(0),yi(1,"div",2,0)(3,"div",3,1)(5,"div",4),mD(6,1),$p(7,mt,0,0,"ng-template",5),jc(),mD(8,2),jc()()),t&2&&(Wv(),Qp("inert",n.expanded?null:""),Wv(2),Zp("id",n.id),Qp("aria-labelledby",n._headerId),Wv(4),Zp("cdkPortalOutlet",n._portal));},dependencies:[aa],styles:[`.mat-expansion-panel {
  box-sizing: content-box;
  display: block;
  margin: 0;
  overflow: hidden;
}
.mat-expansion-panel.mat-expansion-panel-animations-enabled {
  transition: margin 225ms cubic-bezier(0.4, 0, 0.2, 1), box-shadow 280ms cubic-bezier(0.4, 0, 0.2, 1);
}
.mat-expansion-panel {
  position: relative;
  background: var(--mat-expansion-container-background-color, var(--mat-sys-surface));
  color: var(--mat-expansion-container-text-color, var(--mat-sys-on-surface));
  border-radius: var(--mat-expansion-container-shape, 12px);
}
.mat-expansion-panel:not([class*=mat-elevation-z]) {
  box-shadow: var(--mat-expansion-container-elevation-shadow, 0px 3px 1px -2px rgba(0, 0, 0, 0.2), 0px 2px 2px 0px rgba(0, 0, 0, 0.14), 0px 1px 5px 0px rgba(0, 0, 0, 0.12));
}
.mat-accordion .mat-expansion-panel:not(.mat-expanded), .mat-accordion .mat-expansion-panel:not(.mat-expansion-panel-spacing) {
  border-radius: 0;
}
.mat-accordion .mat-expansion-panel:first-of-type {
  border-top-right-radius: var(--mat-expansion-container-shape, 12px);
  border-top-left-radius: var(--mat-expansion-container-shape, 12px);
}
.mat-accordion .mat-expansion-panel:last-of-type {
  border-bottom-right-radius: var(--mat-expansion-container-shape, 12px);
  border-bottom-left-radius: var(--mat-expansion-container-shape, 12px);
}
@media (forced-colors: active) {
  .mat-expansion-panel {
    outline: solid 1px;
  }
}

.mat-expansion-panel-content-wrapper {
  display: grid;
  grid-template-rows: 0fr;
  grid-template-columns: 100%;
}
.mat-expansion-panel-animations-enabled .mat-expansion-panel-content-wrapper {
  transition: grid-template-rows 225ms cubic-bezier(0.4, 0, 0.2, 1);
}
.mat-expansion-panel.mat-expanded > .mat-expansion-panel-content-wrapper {
  grid-template-rows: 1fr;
}
@supports not (grid-template-rows: 0fr) {
  .mat-expansion-panel-content-wrapper {
    height: 0;
  }
  .mat-expansion-panel.mat-expanded > .mat-expansion-panel-content-wrapper {
    height: auto;
  }
}
@media print {
  .mat-expansion-panel-content-wrapper {
    height: 0;
  }
  .mat-expansion-panel.mat-expanded > .mat-expansion-panel-content-wrapper {
    height: auto;
  }
}

.mat-expansion-panel-content {
  display: flex;
  flex-direction: column;
  overflow: visible;
  min-height: 0;
  visibility: hidden;
}
.mat-expansion-panel-animations-enabled .mat-expansion-panel-content {
  transition: visibility 190ms linear;
}
.mat-expansion-panel.mat-expanded > .mat-expansion-panel-content-wrapper > .mat-expansion-panel-content {
  visibility: visible;
}
.mat-expansion-panel-content {
  font-family: var(--mat-expansion-container-text-font, var(--mat-sys-body-large-font));
  font-size: var(--mat-expansion-container-text-size, var(--mat-sys-body-large-size));
  font-weight: var(--mat-expansion-container-text-weight, var(--mat-sys-body-large-weight));
  line-height: var(--mat-expansion-container-text-line-height, var(--mat-sys-body-large-line-height));
  letter-spacing: var(--mat-expansion-container-text-tracking, var(--mat-sys-body-large-tracking));
}

.mat-expansion-panel-body {
  padding: 0 24px 16px;
}

.mat-expansion-panel-spacing {
  margin: 16px 0;
}
.mat-accordion > .mat-expansion-panel-spacing:first-child, .mat-accordion > *:first-child:not(.mat-expansion-panel) .mat-expansion-panel-spacing {
  margin-top: 0;
}
.mat-accordion > .mat-expansion-panel-spacing:last-child, .mat-accordion > *:last-child:not(.mat-expansion-panel) .mat-expansion-panel-spacing {
  margin-bottom: 0;
}

.mat-action-row {
  border-top-style: solid;
  border-top-width: 1px;
  display: flex;
  flex-direction: row;
  justify-content: flex-end;
  padding: 16px 8px 16px 24px;
  border-top-color: var(--mat-expansion-actions-divider-color, var(--mat-sys-outline));
}
.mat-action-row .mat-button-base,
.mat-action-row .mat-mdc-button-base {
  margin-left: 8px;
}
[dir=rtl] .mat-action-row .mat-button-base,
[dir=rtl] .mat-action-row .mat-mdc-button-base {
  margin-left: 0;
  margin-right: 8px;
}
`],encapsulation:2})}return i})();var vt=(()=>{class i{panel=T(xt,{host:true});_element=T(Dr);_focusMonitor=T(hn);_changeDetectorRef=T(L0);_parentChangeSubscription=j$1.EMPTY;constructor(){T(ne$1).load(Js);let e=this.panel,t=T(et,{optional:true}),n=T(new Ah("tabindex"),{optional:true}),o=e.accordion?e.accordion._stateChanges.pipe(en(r=>!!(r.hideToggle||r.togglePosition))):Dt;this.tabIndex=parseInt(n||"")||0,this._parentChangeSubscription=kg(e.opened,e.closed,o,e._inputChanges.pipe(en(r=>!!(r.hideToggle||r.disabled||r.togglePosition)))).subscribe(()=>this._changeDetectorRef.markForCheck()),e.closed.pipe(en(()=>e._containsFocus())).subscribe(()=>this._focusMonitor.focusVia(this._element,"program")),t&&(this.expandedHeight=t.expandedHeight,this.collapsedHeight=t.collapsedHeight);}expandedHeight;collapsedHeight;tabIndex=0;get disabled(){return this.panel.disabled}_toggle(){this.disabled||this.panel.toggle();}_isExpanded(){return this.panel.expanded}_getExpandedState(){return this.panel._getExpandedState()}_getPanelId(){return this.panel.id}_getTogglePosition(){return this.panel.togglePosition}_showToggle(){return !this.panel.hideToggle&&!this.panel.disabled}_getHeaderHeight(){let e=this._isExpanded();return e&&this.expandedHeight?this.expandedHeight:!e&&this.collapsedHeight?this.collapsedHeight:null}_keydown(e){switch(e.keyCode){case 32:case 13:In(e)||(e.preventDefault(),this._toggle());break;default:this.panel.accordion&&this.panel.accordion._handleHeaderKeydown(e);return}}focus(e,t){e?this._focusMonitor.focusVia(this._element,e,t):this._element.nativeElement.focus(t);}ngAfterViewInit(){this._focusMonitor.monitor(this._element).subscribe(e=>{e&&this.panel.accordion&&this.panel.accordion._handleHeaderFocus(this);});}ngOnDestroy(){this._parentChangeSubscription.unsubscribe(),this._focusMonitor.stopMonitoring(this._element);}static \u0275fac=function(t){return new(t||i)};static \u0275cmp=TE({type:i,selectors:[["mat-expansion-panel-header"]],hostAttrs:["role","button",1,"mat-expansion-panel-header","mat-focus-indicator"],hostVars:13,hostBindings:function(t,n){t&1&&nh("click",function(){return n._toggle()})("keydown",function(r){return n._keydown(r)}),t&2&&(Qp("id",n.panel._headerId)("tabindex",n.disabled?-1:n.tabIndex)("aria-controls",n._getPanelId())("aria-expanded",n._isExpanded())("aria-disabled",n.panel.disabled),dh("height",n._getHeaderHeight()),fh("mat-expanded",n._isExpanded())("mat-expansion-toggle-indicator-after",n._getTogglePosition()==="after")("mat-expansion-toggle-indicator-before",n._getTogglePosition()==="before"));},inputs:{expandedHeight:"expandedHeight",collapsedHeight:"collapsedHeight",tabIndex:[2,"tabIndex","tabIndex",e=>e==null?0:V0(e)]},ngContentSelectors:gt,decls:5,vars:3,consts:[[1,"mat-content"],[1,"mat-expansion-indicator"],["xmlns","http://www.w3.org/2000/svg","viewBox","0 -960 960 960","aria-hidden","true","focusable","false"],["d","M480-345 240-585l56-56 184 184 184-184 56 56-240 240Z"]],template:function(t,n){t&1&&(gD(ut),Vc(0,"span",0),mD(1),mD(2,1),mD(3,2),Hc(),QE(4,ft,3,0,"span",1)),t&2&&(fh("mat-content-hide-toggle",!n._showToggle()),Wv(4),ZE(n._showToggle()?4:-1));},styles:[`.mat-expansion-panel-header {
  display: flex;
  flex-direction: row;
  align-items: center;
  padding: 0 24px;
  border-radius: inherit;
}
.mat-expansion-panel-animations-enabled .mat-expansion-panel-header {
  transition: height 225ms cubic-bezier(0.4, 0, 0.2, 1);
}
.mat-expansion-panel-header::before {
  border-radius: inherit;
}
.mat-expansion-panel-header {
  height: var(--mat-expansion-header-collapsed-state-height, 48px);
  font-family: var(--mat-expansion-header-text-font, var(--mat-sys-title-medium-font));
  font-size: var(--mat-expansion-header-text-size, var(--mat-sys-title-medium-size));
  font-weight: var(--mat-expansion-header-text-weight, var(--mat-sys-title-medium-weight));
  line-height: var(--mat-expansion-header-text-line-height, var(--mat-sys-title-medium-line-height));
  letter-spacing: var(--mat-expansion-header-text-tracking, var(--mat-sys-title-medium-tracking));
}
.mat-expansion-panel-header.mat-expanded {
  height: var(--mat-expansion-header-expanded-state-height, 64px);
}
.mat-expansion-panel-header[aria-disabled=true] {
  color: var(--mat-expansion-header-disabled-state-text-color, color-mix(in srgb, var(--mat-sys-on-surface) 38%, transparent));
}
.mat-expansion-panel-header:not([aria-disabled=true]) {
  cursor: pointer;
}
.mat-expansion-panel:not(.mat-expanded) .mat-expansion-panel-header:not([aria-disabled=true]):hover {
  background: var(--mat-expansion-header-hover-state-layer-color, color-mix(in srgb, var(--mat-sys-on-surface) calc(var(--mat-sys-hover-state-layer-opacity) * 100%), transparent));
}
@media (hover: none) {
  .mat-expansion-panel:not(.mat-expanded) .mat-expansion-panel-header:not([aria-disabled=true]):hover {
    background: var(--mat-expansion-container-background-color, var(--mat-sys-surface));
  }
}
.mat-expansion-panel .mat-expansion-panel-header:not([aria-disabled=true]).cdk-keyboard-focused, .mat-expansion-panel .mat-expansion-panel-header:not([aria-disabled=true]).cdk-program-focused {
  background: var(--mat-expansion-header-focus-state-layer-color, color-mix(in srgb, var(--mat-sys-on-surface) calc(var(--mat-sys-focus-state-layer-opacity) * 100%), transparent));
}
.mat-expansion-panel-header._mat-animation-noopable {
  transition: none;
}
.mat-expansion-panel-header:focus, .mat-expansion-panel-header:hover {
  outline: none;
}
.mat-expansion-panel-header.mat-expanded:focus, .mat-expansion-panel-header.mat-expanded:hover {
  background: inherit;
}
.mat-expansion-panel-header.mat-expansion-toggle-indicator-before {
  flex-direction: row-reverse;
}
.mat-expansion-panel-header.mat-expansion-toggle-indicator-before .mat-expansion-indicator {
  margin: 0 16px 0 0;
}
[dir=rtl] .mat-expansion-panel-header.mat-expansion-toggle-indicator-before .mat-expansion-indicator {
  margin: 0 0 0 16px;
}

.mat-content {
  display: flex;
  flex: 1;
  flex-direction: row;
  overflow: hidden;
}
.mat-content.mat-content-hide-toggle {
  margin-right: 8px;
}
[dir=rtl] .mat-content.mat-content-hide-toggle {
  margin-right: 0;
  margin-left: 8px;
}
.mat-expansion-toggle-indicator-before .mat-content.mat-content-hide-toggle {
  margin-left: 24px;
  margin-right: 0;
}
[dir=rtl] .mat-expansion-toggle-indicator-before .mat-content.mat-content-hide-toggle {
  margin-right: 24px;
  margin-left: 0;
}

.mat-expansion-panel-header-title {
  color: var(--mat-expansion-header-text-color, var(--mat-sys-on-surface));
}

.mat-expansion-panel-header-title,
.mat-expansion-panel-header-description {
  display: flex;
  flex-grow: 1;
  flex-basis: 0;
  margin-right: 16px;
  align-items: center;
}
[dir=rtl] .mat-expansion-panel-header-title,
[dir=rtl] .mat-expansion-panel-header-description {
  margin-right: 0;
  margin-left: 16px;
}
.mat-expansion-panel-header[aria-disabled=true] .mat-expansion-panel-header-title,
.mat-expansion-panel-header[aria-disabled=true] .mat-expansion-panel-header-description {
  color: inherit;
}

.mat-expansion-panel-header-description {
  flex-grow: 2;
  color: var(--mat-expansion-header-description-color, var(--mat-sys-on-surface-variant));
}

.mat-expansion-panel-animations-enabled .mat-expansion-indicator {
  transition: transform 225ms cubic-bezier(0.4, 0, 0.2, 1);
}
.mat-expansion-panel-header.mat-expanded .mat-expansion-indicator {
  transform: rotate(180deg);
}
.mat-expansion-indicator::after {
  border-style: solid;
  border-width: 0 2px 2px 0;
  content: "";
  padding: 3px;
  transform: rotate(45deg);
  vertical-align: middle;
  color: var(--mat-expansion-header-indicator-color, var(--mat-sys-on-surface-variant));
  display: var(--mat-expansion-legacy-header-indicator-display, none);
}
.mat-expansion-indicator svg {
  width: 24px;
  height: 24px;
  margin: 0 -8px;
  vertical-align: middle;
  fill: var(--mat-expansion-header-indicator-color, var(--mat-sys-on-surface-variant));
  display: var(--mat-expansion-header-indicator-display, inline-block);
}

@media (forced-colors: active) {
  .mat-expansion-panel-content {
    border-top: 1px solid;
    border-top-left-radius: 0;
    border-top-right-radius: 0;
  }
}
`],encapsulation:2})}return i})(),mn=(()=>{class i{static \u0275fac=function(t){return new(t||i)};static \u0275dir=SE({type:i,selectors:[["mat-panel-description"]],hostAttrs:[1,"mat-expansion-panel-header-description"]})}return i})(),un=(()=>{class i{static \u0275fac=function(t){return new(t||i)};static \u0275dir=SE({type:i,selectors:[["mat-panel-title"]],hostAttrs:[1,"mat-expansion-panel-header-title"]})}return i})(),gn=(()=>{class i extends Ke{_keyManager;_ownHeaders=new ri;_headers;hideToggle=false;displayMode="default";togglePosition="after";ngAfterContentInit(){this._headers.changes.pipe(Hl(this._headers)).subscribe(e=>{this._ownHeaders.reset(e.filter(t=>t.panel.accordion===this)),this._ownHeaders.notifyOnChanges();}),this._keyManager=new at$1(this._ownHeaders).withWrap().withHomeAndEnd();}_handleHeaderKeydown(e){this._keyManager.onKeydown(e);}_handleHeaderFocus(e){this._keyManager.updateActiveItem(e);}ngOnDestroy(){super.ngOnDestroy(),this._keyManager?.destroy(),this._ownHeaders.destroy();}static \u0275fac=(()=>{let e;return function(n){return (e||(e=fy(i)))(n||i)}})();static \u0275dir=SE({type:i,selectors:[["mat-accordion"]],contentQueries:function(t,n,o){if(t&1&&ih(o,vt,5),t&2){let r;vD(r=ID())&&(n._headers=r);}},hostAttrs:[1,"mat-accordion"],hostVars:2,hostBindings:function(t,n){t&2&&fh("mat-accordion-multi",n.multi);},inputs:{hideToggle:[2,"hideToggle","hideToggle",j0],displayMode:"displayMode",togglePosition:"togglePosition"},exportAs:["matAccordion"],features:[JD([{provide:le,useExisting:i}]),Hp]})}return i})(),fn=(()=>{class i{static \u0275fac=function(t){return new(t||i)};static \u0275mod=bE({type:i});static \u0275inj=cu({imports:[Ge,ca,jo]})}return i})();function tt(i){return Error(`Unable to find icon with the name "${i}"`)}function bt(){return Error("Could not find HttpClient for use with Angular Material icons. Please add provideHttpClient() to your providers.")}function nt(i){return Error(`The URL provided to MatIconRegistry was not trusted as a resource URL via Angular's DomSanitizer. Attempted URL was "${i}".`)}function it(i){return Error(`The literal provided to MatIconRegistry was not trusted as safe HTML by Angular's DomSanitizer. Attempted literal was "${i}".`)}var f=class{url;svgText;options;svgElement=null;constructor(a,e,t){this.url=a,this.svgText=e,this.options=t;}},at=(()=>{class i{_httpClient;_sanitizer;_errorHandler;_document;_svgIconConfigs=new Map;_iconSetConfigs=new Map;_cachedIconsByUrl=new Map;_inProgressUrlFetches=new Map;_fontCssClassesByAlias=new Map;_resolvers=[];_defaultFontSetClass=["material-icons","mat-ligature-font"];constructor(e,t,n,o){this._httpClient=e,this._sanitizer=t,this._errorHandler=o,this._document=n;}addSvgIcon(e,t,n){return this.addSvgIconInNamespace("",e,t,n)}addSvgIconLiteral(e,t,n){return this.addSvgIconLiteralInNamespace("",e,t,n)}addSvgIconInNamespace(e,t,n,o){return this._addSvgIconConfig(e,t,new f(n,null,o))}addSvgIconResolver(e){return this._resolvers.push(e),this}addSvgIconLiteralInNamespace(e,t,n,o){let r=this._sanitizer.sanitize(Tr.HTML,n);if(!r)throw it(n);let l=oi(r);return this._addSvgIconConfig(e,t,new f("",l,o))}addSvgIconSet(e,t){return this.addSvgIconSetInNamespace("",e,t)}addSvgIconSetLiteral(e,t){return this.addSvgIconSetLiteralInNamespace("",e,t)}addSvgIconSetInNamespace(e,t,n){return this._addSvgIconSetConfig(e,new f(t,null,n))}addSvgIconSetLiteralInNamespace(e,t,n){let o=this._sanitizer.sanitize(Tr.HTML,t);if(!o)throw it(t);let r=oi(o);return this._addSvgIconSetConfig(e,new f("",r,n))}registerFontClassAlias(e,t=e){return this._fontCssClassesByAlias.set(e,t),this}classNameForFontAlias(e){return this._fontCssClassesByAlias.get(e)||e}setDefaultFontSetClass(...e){return this._defaultFontSetClass=e,this}getDefaultFontSetClass(){return this._defaultFontSetClass}getSvgIconFromUrl(e){let t=this._sanitizer.sanitize(Tr.RESOURCE_URL,e);if(!t)throw nt(e);let n=this._cachedIconsByUrl.get(t);return n?rg(X(n)):this._loadSvgIconFromConfig(new f(e,null)).pipe(Ul(o=>this._cachedIconsByUrl.set(t,o)),Ce(o=>X(o)))}getNamedSvgIcon(e,t=""){let n=ot(t,e),o=this._svgIconConfigs.get(n);if(o)return this._getSvgFromConfig(o);if(o=this._getIconConfigFromResolvers(t,e),o)return this._svgIconConfigs.set(n,o),this._getSvgFromConfig(o);let r=this._iconSetConfigs.get(t);return r?this._getSvgFromIconSetConfigs(e,r):ug(tt(n))}ngOnDestroy(){this._resolvers=[],this._svgIconConfigs.clear(),this._iconSetConfigs.clear(),this._cachedIconsByUrl.clear();}_getSvgFromConfig(e){return e.svgText?rg(X(this._svgElementFromConfig(e))):this._loadSvgIconFromConfig(e).pipe(Ce(t=>X(t)))}_getSvgFromIconSetConfigs(e,t){let n=this._extractIconWithNameFromAnySet(e,t);if(n)return rg(n);let o=t.filter(r=>!r.svgText).map(r=>this._loadSvgIconSetFromConfig(r).pipe(Ki(l=>{let c=`Loading icon set URL: ${this._sanitizer.sanitize(Tr.RESOURCE_URL,r.url)} failed: ${l.message}`;return this._errorHandler.handleError(new Error(c)),rg(null)})));return bg(o).pipe(Ce(()=>{let r=this._extractIconWithNameFromAnySet(e,t);if(!r)throw tt(e);return r}))}_extractIconWithNameFromAnySet(e,t){for(let n=t.length-1;n>=0;n--){let o=t[n];if(o.svgText&&o.svgText.toString().indexOf(e)>-1){let r=this._svgElementFromConfig(o),l=this._extractSvgIconFromSet(r,e,o.options);if(l)return l}}return null}_loadSvgIconFromConfig(e){return this._fetchIcon(e).pipe(Ul(t=>e.svgText=t),Ce(()=>this._svgElementFromConfig(e)))}_loadSvgIconSetFromConfig(e){return e.svgText?rg(null):this._fetchIcon(e).pipe(Ul(t=>e.svgText=t))}_extractSvgIconFromSet(e,t,n){let o=e.querySelector(`[id="${t}"]`);if(!o)return null;let r=o.cloneNode(true);if(r.removeAttribute("id"),r.nodeName.toLowerCase()==="svg")return this._setSvgAttributes(r,n);if(r.nodeName.toLowerCase()==="symbol")return this._setSvgAttributes(this._toSvgElement(r),n);let l=this._svgElementFromString(oi("<svg></svg>"));return l.appendChild(r),this._setSvgAttributes(l,n)}_svgElementFromString(e){let t=this._document.createElement("DIV");t.innerHTML=e;let n=t.querySelector("svg");if(!n)throw Error("<svg> tag not found");return n}_toSvgElement(e){let t=this._svgElementFromString(oi("<svg></svg>")),n=e.attributes;for(let o=0;o<n.length;o++){let{name:r,value:l}=n[o];r!=="id"&&t.setAttribute(r,l);}for(let o=0;o<e.childNodes.length;o++)e.childNodes[o].nodeType===this._document.ELEMENT_NODE&&t.appendChild(e.childNodes[o].cloneNode(true));return t}_setSvgAttributes(e,t){return e.setAttribute("fit",""),e.setAttribute("height","100%"),e.setAttribute("width","100%"),e.setAttribute("preserveAspectRatio","xMidYMid meet"),e.setAttribute("focusable","false"),t&&t.viewBox&&e.setAttribute("viewBox",t.viewBox),e}_fetchIcon(e){let{url:t,options:n}=e,o=n?.withCredentials??false;if(!this._httpClient)throw bt();if(t==null)throw Error(`Cannot fetch icon from URL "${t}".`);let r=this._sanitizer.sanitize(Tr.RESOURCE_URL,t);if(!r)throw nt(t);let l=this._inProgressUrlFetches.get(r);if(l)return l;let d=this._httpClient.get(r,{responseType:"text",withCredentials:o}).pipe(Ce(c=>oi(c)),Vl(()=>this._inProgressUrlFetches.delete(r)),es());return this._inProgressUrlFetches.set(r,d),d}_addSvgIconConfig(e,t,n){return this._svgIconConfigs.set(ot(e,t),n),this}_addSvgIconSetConfig(e,t){let n=this._iconSetConfigs.get(e);return n?n.push(t):this._iconSetConfigs.set(e,[t]),this}_svgElementFromConfig(e){if(!e.svgElement){let t=this._svgElementFromString(e.svgText);this._setSvgAttributes(t,e.options),e.svgElement=t;}return e.svgElement}_getIconConfigFromResolvers(e,t){for(let n=0;n<this._resolvers.length;n++){let o=this._resolvers[n](t,e);if(o)return yt(o)?new f(o.url,null,o.options):new f(o,null)}}static \u0275fac=function(t){return new(t||i)(Me(an,8),Me(ze),Me(or,8),Me(We))};static \u0275prov=re$1({token:i,factory:i.\u0275fac,providedIn:"root"})}return i})();function X(i){return i.cloneNode(true)}function ot(i,a){return i+":"+a}function yt(i){return !!(i.url&&i.options)}var Et=["*"],St=new x("MAT_ICON_DEFAULT_OPTIONS"),Ct=new x("mat-icon-location",{providedIn:"root",factory:()=>{let i=T(or),a=i?i.location:null;return {getPathname:()=>a?a.pathname+a.search:""}}}),rt=["clip-path","color-profile","src","cursor","fill","filter","marker","marker-start","marker-mid","marker-end","mask","stroke"],It=rt.map(i=>`[${i}]`).join(", "),wt=/^url\(['"]?#(.*?)['"]?\)$/,Hn=(()=>{class i{_elementRef=T(Dr);_iconRegistry=T(at);_location=T(Ct);_errorHandler=T(We);_defaultColor;get color(){return this._color||this._defaultColor}set color(e){this._color=e;}_color;inline=false;get svgIcon(){return this._svgIcon}set svgIcon(e){e!==this._svgIcon&&(e?this._updateSvgIcon(e):this._svgIcon&&this._clearSvgElement(),this._svgIcon=e);}_svgIcon;get fontSet(){return this._fontSet}set fontSet(e){let t=this._cleanupFontValue(e);t!==this._fontSet&&(this._fontSet=t,this._updateFontIconClasses());}_fontSet;get fontIcon(){return this._fontIcon}set fontIcon(e){let t=this._cleanupFontValue(e);t!==this._fontIcon&&(this._fontIcon=t,this._updateFontIconClasses());}_fontIcon;_previousFontSetClass=[];_previousFontIconClass;_svgName=null;_svgNamespace=null;_previousPath;_elementsWithExternalReferences;_currentIconFetch=j$1.EMPTY;constructor(){let e=T(new Ah("aria-hidden"),{optional:true}),t=T(St,{optional:true});t&&(t.color&&(this.color=this._defaultColor=t.color),t.fontSet&&(this.fontSet=t.fontSet)),e||this._elementRef.nativeElement.setAttribute("aria-hidden","true");}_splitIconName(e){if(!e)return ["",""];let t=e.split(":");switch(t.length){case 1:return ["",t[0]];case 2:return t;default:throw Error(`Invalid icon name: "${e}"`)}}ngOnInit(){this._updateFontIconClasses();}ngAfterViewChecked(){let e=this._elementsWithExternalReferences;if(e&&e.size){let t=this._location.getPathname();t!==this._previousPath&&(this._previousPath=t,this._prependPathToReferences(t));}}ngOnDestroy(){this._currentIconFetch.unsubscribe(),this._elementsWithExternalReferences&&this._elementsWithExternalReferences.clear();}_usingFontIcon(){return !this.svgIcon}_setSvgElement(e){this._clearSvgElement();let t=this._location.getPathname();this._previousPath=t,this._cacheChildrenWithExternalReferences(e),this._prependPathToReferences(t),this._elementRef.nativeElement.appendChild(e);}_clearSvgElement(){let e=this._elementRef.nativeElement,t=e.childNodes.length;for(this._elementsWithExternalReferences&&this._elementsWithExternalReferences.clear();t--;){let n=e.childNodes[t];(n.nodeType!==1||n.nodeName.toLowerCase()==="svg")&&n.remove();}}_updateFontIconClasses(){if(!this._usingFontIcon())return;let e=this._elementRef.nativeElement,t=(this.fontSet?this._iconRegistry.classNameForFontAlias(this.fontSet).split(/ +/):this._iconRegistry.getDefaultFontSetClass()).filter(n=>n.length>0);this._previousFontSetClass.forEach(n=>e.classList.remove(n)),t.forEach(n=>e.classList.add(n)),this._previousFontSetClass=t,this.fontIcon!==this._previousFontIconClass&&!t.includes("mat-ligature-font")&&(this._previousFontIconClass&&e.classList.remove(this._previousFontIconClass),this.fontIcon&&e.classList.add(this.fontIcon),this._previousFontIconClass=this.fontIcon);}_cleanupFontValue(e){return typeof e=="string"?e.trim().split(" ")[0]:e}_prependPathToReferences(e){let t=this._elementsWithExternalReferences;t&&t.forEach((n,o)=>{n.forEach(r=>{o.setAttribute(r.name,`url('${e}#${r.value}')`);});});}_cacheChildrenWithExternalReferences(e){let t=e.querySelectorAll(It),n=this._elementsWithExternalReferences=this._elementsWithExternalReferences||new Map;for(let o=0;o<t.length;o++)rt.forEach(r=>{let l=t[o],d=l.getAttribute(r),c=d?d.match(wt):null;if(c){let h=n.get(l);h||(h=[],n.set(l,h)),h.push({name:r,value:c[1]});}});}_updateSvgIcon(e){if(this._svgNamespace=null,this._svgName=null,this._currentIconFetch.unsubscribe(),e){let[t,n]=this._splitIconName(e);t&&(this._svgNamespace=t),n&&(this._svgName=n),this._currentIconFetch=this._iconRegistry.getNamedSvgIcon(n,t).pipe(tn(1)).subscribe(o=>this._setSvgElement(o),o=>{let r=`Error retrieving icon ${t}:${n}! ${o.message}`;this._errorHandler.handleError(new Error(r));});}}static \u0275fac=function(t){return new(t||i)};static \u0275cmp=TE({type:i,selectors:[["mat-icon"]],hostAttrs:["role","img",1,"mat-icon","notranslate"],hostVars:10,hostBindings:function(t,n){t&2&&(Qp("data-mat-icon-type",n._usingFontIcon()?"font":"svg")("data-mat-icon-name",n._svgName||n.fontIcon)("data-mat-icon-namespace",n._svgNamespace||n.fontSet)("fontIcon",n._usingFontIcon()?n.fontIcon:null),RD(n.color?"mat-"+n.color:""),fh("mat-icon-inline",n.inline)("mat-icon-no-color",n.color!=="primary"&&n.color!=="accent"&&n.color!=="warn"));},inputs:{color:"color",inline:[2,"inline","inline",j0],svgIcon:"svgIcon",fontSet:"fontSet",fontIcon:"fontIcon"},exportAs:["matIcon"],ngContentSelectors:Et,decls:1,vars:0,template:function(t,n){t&1&&(gD(),mD(0));},styles:[`mat-icon, mat-icon.mat-primary, mat-icon.mat-accent, mat-icon.mat-warn {
  color: var(--mat-icon-color, inherit);
}

.mat-icon {
  -webkit-user-select: none;
  user-select: none;
  background-repeat: no-repeat;
  display: inline-block;
  fill: currentColor;
  height: 24px;
  width: 24px;
  overflow: hidden;
}
.mat-icon.mat-icon-inline {
  font-size: inherit;
  height: inherit;
  line-height: inherit;
  width: inherit;
}
.mat-icon.mat-ligature-font[fontIcon]::before {
  content: attr(fontIcon);
}

[dir=rtl] .mat-icon-rtl-mirror {
  transform: scale(-1, 1);
}

.mat-form-field:not(.mat-form-field-appearance-legacy) .mat-form-field-prefix .mat-icon,
.mat-form-field:not(.mat-form-field-appearance-legacy) .mat-form-field-suffix .mat-icon {
  display: block;
}
.mat-form-field:not(.mat-form-field-appearance-legacy) .mat-form-field-prefix .mat-icon-button .mat-icon,
.mat-form-field:not(.mat-form-field-appearance-legacy) .mat-form-field-suffix .mat-icon-button .mat-icon {
  margin: auto;
}
`],encapsulation:2})}return i})(),Ln=(()=>{class i{static \u0275fac=function(t){return new(t||i)};static \u0275mod=bE({type:i});static \u0275inj=cu({imports:[jo]})}return i})();var ce=class{_box;_destroyed=new ne;_resizeSubject=new ne;_resizeObserver;_elementObservables=new Map;constructor(a){this._box=a,typeof ResizeObserver<"u"&&(this._resizeObserver=new ResizeObserver(e=>this._resizeSubject.next(e)));}observe(a){return this._elementObservables.has(a)||this._elementObservables.set(a,new _(e=>{let t=this._resizeSubject.subscribe(e);return this._resizeObserver?.observe(a,{box:this._box}),()=>{this._resizeObserver?.unobserve(a),t.unsubscribe(),this._elementObservables.delete(a);}}).pipe(en(e=>e.some(t=>t.target===a)),Hg({bufferSize:1,refCount:true}),Bl(this._destroyed))),this._elementObservables.get(a)}destroy(){this._destroyed.next(),this._destroyed.complete(),this._resizeSubject.complete(),this._elementObservables.clear();}},Qn=(()=>{class i{_cleanupErrorListener;_observers=new Map;_ngZone=T(Se);constructor(){}ngOnDestroy(){for(let[,e]of this._observers)e.destroy();this._observers.clear(),this._cleanupErrorListener?.();}observe(e,t){let n=t?.box||"content-box";return this._observers.has(n)||this._observers.set(n,new ce(n)),this._observers.get(n).observe(e)}static \u0275fac=function(t){return new(t||i)};static \u0275prov=Er({token:i,factory:i.\u0275fac})}return i})();export{Hn as H,Ln as L,Qn as Q,Xe as X,fn as f,gn as g,j,mn as m,se as s,un as u,vt as v,xt as x};