import {T,aR as vr,aV as YF,aK as Me$1,aW as j,aX as nt$1,aY as ei,aH as He$1,aZ as _,a_ as N,a$ as ye,b0 as Eg,b1 as ug,s as sE,b2 as Co,b3 as tc,b4 as gt$1,b5 as JF,b6 as XF,b7 as XE,h as hi,q as qp,P as PE,k as kc,A as AE,F as Fp,b8 as Pp,_ as _v,L as LE,R as RE,X as Xp,aM as LD,ah as Lp,as as mD,an as Jp,aO as zp,aP as nD,aQ as rD,b9 as Gp,ba as Ni,bb as te,bc as F,bd as oo,be as E,aL as Sm,bf as Ap,bg as dr,bh as dE,bi as Pt,aS as lI,aw as pe$1,bj as gh,ax as nv,a as jp,bk as eD,bl as zm,bm as hr,bn as Sp,bo as Er,bp as ot,Y as xp,bq as yr,$ as $E,d as Tu,K as KE,ai as iD,e as Cu,br as st,aB as Bh,H as vl,bs as Dg,bt as _e$1,bu as It$1,aA as El,bv as M,bw as Ig,g as Gt,bx as Kn,by as Pn,aN as yo,bz as _$1,M as MD,c as sh,bA as _t,al as OE}from'./main-GY4A6SCU.js';import {g as Ae$1,h as gi}from'./chunk-F_UBP3nh.js';var gt=class{_multiple;_emitChanges;compareWith;_selection=new Set;_deselectedToEmit=[];_selectedToEmit=[];_selected=null;get selected(){return this._selected||(this._selected=Array.from(this._selection.values())),this._selected}changed=new te;bulk={select:r=>this._select(r),deselect:r=>this._deselect(r),setSelection:r=>this._setSelection(r)};constructor(r=false,t,e=true,a){this._multiple=r,this._emitChanges=e,this.compareWith=a,t&&t.length&&(r?t.forEach(i=>this._markSelected(i)):this._markSelected(t[0]),this._selectedToEmit.length=0);}select(...r){return this._select(r)}deselect(...r){return this._deselect(r)}setSelection(...r){return this._setSelection(r)}toggle(r){return this.isSelected(r)?this.deselect(r):this.select(r)}clear(r=true){this._unmarkAll();let t=this._hasQueuedChanges();return r&&this._emitChangeEvent(),t}isSelected(r){return this._selection.has(this._getConcreteValue(r))}isEmpty(){return this._selection.size===0}hasValue(){return !this.isEmpty()}sort(r){this._multiple&&this.selected&&this._selected.sort(r);}isMultipleSelection(){return this._multiple}_select(r){this._verifyValueAssignment(r),r.forEach(e=>this._markSelected(e));let t=this._hasQueuedChanges();return this._emitChangeEvent(),t}_deselect(r){this._verifyValueAssignment(r),r.forEach(e=>this._unmarkSelected(e));let t=this._hasQueuedChanges();return this._emitChangeEvent(),t}_setSelection(r){this._verifyValueAssignment(r);let t=this.selected,e=new Set(r.map(i=>this._getConcreteValue(i)));r.forEach(i=>this._markSelected(i)),t.filter(i=>!e.has(this._getConcreteValue(i,e))).forEach(i=>this._unmarkSelected(i));let a=this._hasQueuedChanges();return this._emitChangeEvent(),a}_emitChangeEvent(){this._selected=null,(this._selectedToEmit.length||this._deselectedToEmit.length)&&(this.changed.next({source:this,added:this._selectedToEmit,removed:this._deselectedToEmit}),this._deselectedToEmit=[],this._selectedToEmit=[]);}_markSelected(r){r=this._getConcreteValue(r),this.isSelected(r)||(this._multiple||this._unmarkAll(),this.isSelected(r)||this._selection.add(r),this._emitChanges&&this._selectedToEmit.push(r));}_unmarkSelected(r){r=this._getConcreteValue(r),this.isSelected(r)&&(this._selection.delete(r),this._emitChanges&&this._deselectedToEmit.push(r));}_unmarkAll(){this.isEmpty()||this._selection.forEach(r=>this._unmarkSelected(r));}_verifyValueAssignment(r){r.length>1&&this._multiple;}_hasQueuedChanges(){return !!(this._deselectedToEmit.length||this._selectedToEmit.length)}_getConcreteValue(r,t){if(this.compareWith){t=t??this._selection;for(let e of t)if(this.compareWith(r,e))return e;return r}else return r}};var yt=(()=>{class n{_listeners=[];notify(t,e){for(let a of this._listeners)a(t,e);}listen(t){return this._listeners.push(t),()=>{this._listeners=this._listeners.filter(e=>t!==e);}}ngOnDestroy(){this._listeners=[];}static \u0275fac=function(e){return new(e||n)};static \u0275prov=yr({token:n,factory:n.\u0275fac})}return n})();var ee=class{applyChanges(r,t,e,a,i){r.forEachOperation((s,f,v)=>{let w,E;if(s.previousIndex==null){let it=e(s,f,v);w=t.createEmbeddedView(it.templateRef,it.context,it.index),E=_$1.INSERTED;}else v==null?(t.remove(f),E=_$1.REMOVED):(w=t.get(f),t.move(w,v),E=_$1.MOVED);i&&i({context:w?.context,operation:E,record:s});});}detach(){}};var le=["*"],ae=(()=>{class n{labelPosition="after";static \u0275fac=function(e){return new(e||n)};static \u0275cmp=sE({type:n,selectors:[["div","mat-internal-form-field",""]],hostAttrs:[1,"mdc-form-field","mat-internal-form-field"],hostVars:2,hostBindings:function(e,a){e&2&&Xp("mdc-form-field--align-end",a.labelPosition==="before");},inputs:{labelPosition:"labelPosition"},ngContentSelectors:le,decls:1,vars:0,template:function(e,a){e&1&&(XE(),eD(0));},styles:[`.mat-internal-form-field {
  -moz-osx-font-smoothing: grayscale;
  -webkit-font-smoothing: antialiased;
  display: inline-flex;
  align-items: center;
  vertical-align: middle;
}
.mat-internal-form-field > label {
  margin-left: 0;
  margin-right: auto;
  padding-left: 4px;
  padding-right: 0;
  order: 0;
}
[dir=rtl] .mat-internal-form-field > label {
  margin-left: auto;
  margin-right: 0;
  padding-left: 0;
  padding-right: 4px;
}

.mdc-form-field--align-end > label {
  margin-left: auto;
  margin-right: 0;
  padding-left: 0;
  padding-right: 4px;
  order: -1;
}
[dir=rtl] .mdc-form-field--align-end .mdc-form-field--align-end label {
  margin-left: 0;
  margin-right: auto;
  padding-left: 4px;
  padding-right: 0;
}
`],encapsulation:2})}return n})();var he=["input"],ue=["formField"],_e=["*"],nt=class{source;value;constructor(r,t){this.source=r,this.value=t;}},pe={provide:Ae$1,useExisting:yo(()=>ge),multi:true},ne=new N("MatRadioGroup"),fe=new N("mat-radio-default-options",{providedIn:"root",factory:()=>({color:"accent",disabledInteractive:false})}),ge=(()=>{class n{_changeDetector=T(YF);_value=null;_name=T(ye).getId("mat-radio-group-");_selected=null;_isInitialized=false;_labelPosition="after";_disabled=false;_required=false;_buttonChanges;_controlValueAccessorChangeFn=()=>{};onTouched=()=>{};change=new He$1;_radios;color;get name(){return this._name}set name(t){this._name=t,this._updateRadioButtonNames();}get labelPosition(){return this._labelPosition}set labelPosition(t){this._labelPosition=t==="before"?"before":"after",this._markRadiosForCheck();}get value(){return this._value}set value(t){this._value!==t&&(this._value=t,this._updateSelectedRadioFromValue(),this._checkSelectedRadioButton());}_checkSelectedRadioButton(){this._selected&&!this._selected.checked&&(this._selected.checked=true);}get selected(){return this._selected}set selected(t){this._selected=t,this.value=t?t.value:null,this._checkSelectedRadioButton();}get disabled(){return this._disabled}set disabled(t){this._disabled=t,this._markRadiosForCheck();}get required(){return this._required}set required(t){this._required=t,this._markRadiosForCheck();}get disabledInteractive(){return this._disabledInteractive}set disabledInteractive(t){this._disabledInteractive=t,this._markRadiosForCheck();}_disabledInteractive=false;ngAfterContentInit(){this._isInitialized=true,this._buttonChanges=this._radios.changes.subscribe(()=>{this.selected&&!this._radios.find(t=>t===this.selected)&&(this._selected=null);});}ngOnDestroy(){this._buttonChanges?.unsubscribe();}_touch(){this.onTouched&&this.onTouched();}_updateRadioButtonNames(){this._radios&&this._radios.forEach(t=>{t.name=this.name,t._markForCheck();});}_updateSelectedRadioFromValue(){let t=this._selected!==null&&this._selected.value===this._value;this._radios&&!t&&(this._selected=null,this._radios.forEach(e=>{e.checked=this.value===e.value,e.checked&&(this._selected=e);}));}_emitChangeEvent(){this._isInitialized&&this.change.emit(new nt(this._selected,this._value));}_markRadiosForCheck(){this._radios&&this._radios.forEach(t=>t._markForCheck());}writeValue(t){this.value=t,this._changeDetector.markForCheck();}registerOnChange(t){this._controlValueAccessorChangeFn=t;}registerOnTouched(t){this.onTouched=t;}setDisabledState(t){this.disabled=t,this._changeDetector.markForCheck();}static \u0275fac=function(e){return new(e||n)};static \u0275dir=dE({type:n,selectors:[["mat-radio-group"]],contentQueries:function(e,a,i){if(e&1&&Gp(i,ve,5),e&2){let s;nD(s=rD())&&(a._radios=s);}},hostAttrs:["role","radiogroup",1,"mat-mdc-radio-group"],inputs:{color:"color",name:"name",labelPosition:"labelPosition",value:"value",selected:"selected",disabled:[2,"disabled","disabled",JF],required:[2,"required","required",JF],disabledInteractive:[2,"disabledInteractive","disabledInteractive",JF]},outputs:{change:"change"},exportAs:["matRadioGroup"],features:[LD([pe,{provide:ne,useExisting:n}])]})}return n})(),ve=(()=>{class n{_elementRef=T(vr);_changeDetector=T(YF);_focusMonitor=T(Pt);_radioDispatcher=T(yt);_defaultOptions=T(fe,{optional:true});_ngZone=T(Me$1);_renderer=T(lI);_uniqueId=T(ye).getId("mat-radio-");_cleanupClick;id=this._uniqueId;name;ariaLabel;ariaLabelledby;ariaDescribedby;disableRipple=false;tabIndex=0;get checked(){return this._checked}set checked(t){this._checked!==t&&(this._checked=t,t&&this.radioGroup&&this.radioGroup.value!==this.value?this.radioGroup.selected=this:!t&&this.radioGroup&&this.radioGroup.value===this.value&&(this.radioGroup.selected=null),t&&this._radioDispatcher.notify(this.id,this.name),this._changeDetector.markForCheck());}get value(){return this._value}set value(t){this._value!==t&&(this._value=t,this.radioGroup!==null&&(this.checked||(this.checked=this.radioGroup.value===t),this.checked&&(this.radioGroup.selected=this)));}get labelPosition(){return this._labelPosition||this.radioGroup&&this.radioGroup.labelPosition||"after"}set labelPosition(t){this._labelPosition=t;}_labelPosition;get disabled(){return this._disabled||this.radioGroup!==null&&this.radioGroup.disabled}set disabled(t){this._setDisabled(t);}get required(){return this._required||this.radioGroup&&this.radioGroup.required}set required(t){t!==this._required&&this._changeDetector.markForCheck(),this._required=t;}get color(){return this._color||this.radioGroup&&this.radioGroup.color||this._defaultOptions&&this._defaultOptions.color||"accent"}set color(t){this._color=t;}_color;get disabledInteractive(){return this._disabledInteractive||this.radioGroup!==null&&this.radioGroup.disabledInteractive}set disabledInteractive(t){this._disabledInteractive=t;}_disabledInteractive;change=new He$1;radioGroup;get inputId(){return `${this.id||this._uniqueId}-input`}_checked=false;_disabled=false;_required=false;_value=null;_removeUniqueSelectionListener=()=>{};_previousTabIndex;_inputElement;_rippleTrigger;_noopAnimations=nt$1();_injector=T(pe$1);constructor(){T(F).load(oo);let t=T(ne,{optional:true}),e=T(new gh("tabindex"),{optional:true});this.radioGroup=t,this._disabledInteractive=this._defaultOptions?.disabledInteractive??false,e&&(this.tabIndex=XF(e,0));}focus(t,e){e?this._focusMonitor.focusVia(this._inputElement,e,t):this._inputElement.nativeElement.focus(t);}_markForCheck(){this._changeDetector.markForCheck();}ngOnInit(){this.radioGroup&&(this.checked=this.radioGroup.value===this._value,this.checked&&(this.radioGroup.selected=this),this.name=this.radioGroup.name),this._removeUniqueSelectionListener=this._radioDispatcher.listen((t,e)=>{t!==this.id&&e===this.name&&(this.checked=false);});}ngDoCheck(){this._updateTabIndex();}ngAfterViewInit(){this._updateTabIndex(),this._focusMonitor.monitor(this._elementRef,true).subscribe(t=>{!t&&this.radioGroup&&this.radioGroup._touch();}),this._ngZone.runOutsideAngular(()=>{this._cleanupClick=this._renderer.listen(this._inputElement.nativeElement,"click",this._onInputClick);});}ngOnDestroy(){this._cleanupClick?.(),this._focusMonitor.stopMonitoring(this._elementRef),this._removeUniqueSelectionListener();}_emitChangeEvent(){this.change.emit(new nt(this,this._value));}_isRippleDisabled(){return this.disableRipple||this.disabled}_onInputInteraction(t){if(t.stopPropagation(),!this.checked&&!this.disabled){let e=this.radioGroup&&this.value!==this.radioGroup.value;this.checked=true,this._emitChangeEvent(),this.radioGroup&&(this.radioGroup._controlValueAccessorChangeFn(this.value),e&&this.radioGroup._emitChangeEvent());}}_onTouchTargetClick(t){this._onInputInteraction(t),(!this.disabled||this.disabledInteractive)&&this._inputElement?.nativeElement.focus();}_setDisabled(t){this._disabled!==t&&(this._disabled=t,this._changeDetector.markForCheck());}_onInputClick=t=>{this.disabled&&this.disabledInteractive&&t.preventDefault();};_updateTabIndex(){let t=this.radioGroup,e;if(!t||!t.selected||this.disabled?e=this.tabIndex:e=t.selected===this?this.tabIndex:-1,e!==this._previousTabIndex){let a=this._inputElement?.nativeElement;a&&(a.setAttribute("tabindex",e+""),this._previousTabIndex=e,nv(()=>{queueMicrotask(()=>{t&&t.selected&&t.selected!==this&&document.activeElement===a&&(t.selected?._inputElement.nativeElement.focus(),document.activeElement===a&&this._inputElement.nativeElement.blur());});},{injector:this._injector}));}}static \u0275fac=function(e){return new(e||n)};static \u0275cmp=sE({type:n,selectors:[["mat-radio-button"]],viewQuery:function(e,a){if(e&1&&zp(he,5)(ue,7,vr),e&2){let i;nD(i=rD())&&(a._inputElement=i.first),nD(i=rD())&&(a._rippleTrigger=i.first);}},hostAttrs:[1,"mat-mdc-radio-button"],hostVars:19,hostBindings:function(e,a){e&1&&qp("focus",function(){return a._inputElement.nativeElement.focus()}),e&2&&(Lp("id",a.id)("tabindex",null)("aria-label",null)("aria-labelledby",null)("aria-describedby",null),Xp("mat-primary",a.color==="primary")("mat-accent",a.color==="accent")("mat-warn",a.color==="warn")("mat-mdc-radio-checked",a.checked)("mat-mdc-radio-disabled",a.disabled)("mat-mdc-radio-disabled-interactive",a.disabledInteractive)("_mat-animation-noopable",a._noopAnimations));},inputs:{id:"id",name:"name",ariaLabel:[0,"aria-label","ariaLabel"],ariaLabelledby:[0,"aria-labelledby","ariaLabelledby"],ariaDescribedby:[0,"aria-describedby","ariaDescribedby"],disableRipple:[2,"disableRipple","disableRipple",JF],tabIndex:[2,"tabIndex","tabIndex",t=>t==null?0:XF(t)],checked:[2,"checked","checked",JF],value:"value",labelPosition:"labelPosition",disabled:[2,"disabled","disabled",JF],required:[2,"required","required",JF],color:"color",disabledInteractive:[2,"disabledInteractive","disabledInteractive",JF]},outputs:{change:"change"},exportAs:["matRadioButton"],ngContentSelectors:_e,decls:13,vars:17,consts:[["formField",""],["input",""],["mat-internal-form-field","",3,"labelPosition"],[1,"mdc-radio"],["aria-hidden","true",1,"mat-mdc-radio-touch-target",3,"click"],["type","radio","aria-invalid","false",1,"mdc-radio__native-control",3,"change","id","checked","disabled","required"],["aria-hidden","true",1,"mdc-radio__background"],[1,"mdc-radio__outer-circle"],[1,"mdc-radio__inner-circle"],["mat-ripple","","aria-hidden","true",1,"mat-radio-ripple","mat-focus-indicator",3,"matRippleTrigger","matRippleDisabled","matRippleCentered"],[1,"mat-ripple-element","mat-radio-persistent-ripple"],[1,"mdc-label",3,"for"]],template:function(e,a){e&1&&(XE(),hi(0,"div",2,0)(2,"div",3)(3,"div",4),qp("click",function(s){return a._onTouchTargetClick(s)}),kc(),hi(4,"input",5,1),qp("change",function(s){return a._onInputInteraction(s)}),kc(),hi(6,"div",6),jp(7,"div",7)(8,"div",8),kc(),hi(9,"div",9),jp(10,"div",10),kc()(),hi(11,"label",11),eD(12),kc()()),e&2&&(Fp("labelPosition",a.labelPosition),_v(2),Xp("mdc-radio--disabled",a.disabled),_v(2),Fp("id",a.inputId)("checked",a.checked)("disabled",a.disabled&&!a.disabledInteractive)("required",a.required),Lp("name",a.name)("value",a.value)("aria-label",a.ariaLabel)("aria-labelledby",a.ariaLabelledby)("aria-describedby",a.ariaDescribedby)("aria-disabled",a.disabled&&a.disabledInteractive?"true":null),_v(5),Fp("matRippleTrigger",a._rippleTrigger.nativeElement)("matRippleDisabled",a._isRippleDisabled())("matRippleCentered",true),_v(2),Fp("for",a.inputId));},dependencies:[tc,ae],styles:[`.mat-mdc-radio-button {
  -webkit-tap-highlight-color: transparent;
}
.mat-mdc-radio-button .mdc-radio {
  display: inline-block;
  position: relative;
  flex: 0 0 auto;
  box-sizing: content-box;
  width: 20px;
  height: 20px;
  will-change: opacity, transform, border-color, color;
  padding: calc((var(--mat-radio-state-layer-size, 40px) - 20px) / 2);
  cursor: pointer;
}
.mat-mdc-radio-button .mdc-radio:hover > .mdc-radio__native-control:not([disabled]):not(:focus) ~ .mdc-radio__background::before {
  opacity: 0.04;
  transform: scale(1);
}
.mat-mdc-radio-button .mdc-radio:hover > .mdc-radio__native-control:not([disabled]) ~ .mdc-radio__background > .mdc-radio__outer-circle {
  border-color: var(--mat-radio-unselected-hover-icon-color, var(--mat-sys-on-surface));
}
.mat-mdc-radio-button .mdc-radio:hover > .mdc-radio__native-control:enabled:checked + .mdc-radio__background > .mdc-radio__outer-circle {
  border-color: var(--mat-radio-selected-hover-icon-color, var(--mat-sys-primary));
}
.mat-mdc-radio-button .mdc-radio:hover > .mdc-radio__native-control:enabled:checked + .mdc-radio__background > .mdc-radio__inner-circle {
  background-color: var(--mat-radio-selected-hover-icon-color, var(--mat-sys-primary, currentColor));
}
.mat-mdc-radio-button .mdc-radio:active > .mdc-radio__native-control:enabled:not(:checked) + .mdc-radio__background > .mdc-radio__outer-circle {
  border-color: var(--mat-radio-unselected-pressed-icon-color, var(--mat-sys-on-surface));
}
.mat-mdc-radio-button .mdc-radio:active > .mdc-radio__native-control:enabled:checked + .mdc-radio__background > .mdc-radio__outer-circle {
  border-color: var(--mat-radio-selected-pressed-icon-color, var(--mat-sys-primary));
}
.mat-mdc-radio-button .mdc-radio:active > .mdc-radio__native-control:enabled:checked + .mdc-radio__background > .mdc-radio__inner-circle {
  background-color: var(--mat-radio-selected-pressed-icon-color, var(--mat-sys-primary, currentColor));
}
.mat-mdc-radio-button .mdc-radio__background {
  display: inline-block;
  position: relative;
  box-sizing: border-box;
  width: 20px;
  height: 20px;
}
.mat-mdc-radio-button .mdc-radio__background::before {
  position: absolute;
  transform: scale(0, 0);
  border-radius: 50%;
  opacity: 0;
  pointer-events: none;
  content: "";
  transition: opacity 90ms cubic-bezier(0.4, 0, 0.6, 1), transform 90ms cubic-bezier(0.4, 0, 0.6, 1);
  width: var(--mat-radio-state-layer-size, 40px);
  height: var(--mat-radio-state-layer-size, 40px);
  top: calc(-1 * (var(--mat-radio-state-layer-size, 40px) - 20px) / 2);
  left: calc(-1 * (var(--mat-radio-state-layer-size, 40px) - 20px) / 2);
}
.mat-mdc-radio-button .mdc-radio__outer-circle {
  position: absolute;
  top: 0;
  left: 0;
  box-sizing: border-box;
  width: 100%;
  height: 100%;
  border-width: 2px;
  border-style: solid;
  border-radius: 50%;
  transition: border-color 90ms cubic-bezier(0.4, 0, 0.6, 1);
}
.mat-mdc-radio-button .mdc-radio__inner-circle {
  position: absolute;
  top: 0;
  left: 0;
  box-sizing: border-box;
  width: 100%;
  height: 100%;
  transform: scale(0);
  border-radius: 50%;
  transition: transform 90ms cubic-bezier(0.4, 0, 0.6, 1), background-color 90ms cubic-bezier(0.4, 0, 0.6, 1);
}
@media (forced-colors: active) {
  .mat-mdc-radio-button .mdc-radio__inner-circle {
    background-color: CanvasText !important;
  }
}
.mat-mdc-radio-button .mdc-radio__native-control {
  position: absolute;
  margin: 0;
  padding: 0;
  opacity: 0;
  top: 0;
  right: 0;
  left: 0;
  cursor: inherit;
  z-index: 1;
  width: var(--mat-radio-state-layer-size, 40px);
  height: var(--mat-radio-state-layer-size, 40px);
}
.mat-mdc-radio-button .mdc-radio__native-control:checked + .mdc-radio__background, .mat-mdc-radio-button .mdc-radio__native-control:disabled + .mdc-radio__background {
  transition: opacity 90ms cubic-bezier(0, 0, 0.2, 1), transform 90ms cubic-bezier(0, 0, 0.2, 1);
}
.mat-mdc-radio-button .mdc-radio__native-control:checked + .mdc-radio__background > .mdc-radio__outer-circle, .mat-mdc-radio-button .mdc-radio__native-control:disabled + .mdc-radio__background > .mdc-radio__outer-circle {
  transition: border-color 90ms cubic-bezier(0, 0, 0.2, 1);
}
.mat-mdc-radio-button .mdc-radio__native-control:checked + .mdc-radio__background > .mdc-radio__inner-circle, .mat-mdc-radio-button .mdc-radio__native-control:disabled + .mdc-radio__background > .mdc-radio__inner-circle {
  transition: transform 90ms cubic-bezier(0, 0, 0.2, 1), background-color 90ms cubic-bezier(0, 0, 0.2, 1);
}
.mat-mdc-radio-button .mdc-radio__native-control:focus + .mdc-radio__background::before {
  transform: scale(1);
  opacity: 0.12;
  transition: opacity 90ms cubic-bezier(0, 0, 0.2, 1), transform 90ms cubic-bezier(0, 0, 0.2, 1);
}
.mat-mdc-radio-button .mdc-radio__native-control:disabled:not(:checked) + .mdc-radio__background > .mdc-radio__outer-circle {
  border-color: var(--mat-radio-disabled-unselected-icon-color, var(--mat-sys-on-surface));
  opacity: var(--mat-radio-disabled-unselected-icon-opacity, 0.38);
}
.mat-mdc-radio-button .mdc-radio__native-control:disabled + .mdc-radio__background {
  cursor: default;
}
.mat-mdc-radio-button .mdc-radio__native-control:disabled + .mdc-radio__background > .mdc-radio__outer-circle {
  border-color: var(--mat-radio-disabled-selected-icon-color, var(--mat-sys-on-surface));
  opacity: var(--mat-radio-disabled-selected-icon-opacity, 0.38);
}
.mat-mdc-radio-button .mdc-radio__native-control:disabled + .mdc-radio__background > .mdc-radio__inner-circle {
  background-color: var(--mat-radio-disabled-selected-icon-color, var(--mat-sys-on-surface, currentColor));
  opacity: var(--mat-radio-disabled-selected-icon-opacity, 0.38);
}
.mat-mdc-radio-button .mdc-radio__native-control:enabled:not(:checked) + .mdc-radio__background > .mdc-radio__outer-circle {
  border-color: var(--mat-radio-unselected-icon-color, var(--mat-sys-on-surface-variant));
}
.mat-mdc-radio-button .mdc-radio__native-control:enabled:checked + .mdc-radio__background > .mdc-radio__outer-circle {
  border-color: var(--mat-radio-selected-icon-color, var(--mat-sys-primary));
}
.mat-mdc-radio-button .mdc-radio__native-control:enabled:checked + .mdc-radio__background > .mdc-radio__inner-circle {
  background-color: var(--mat-radio-selected-icon-color, var(--mat-sys-primary, currentColor));
}
.mat-mdc-radio-button .mdc-radio__native-control:enabled:focus:checked + .mdc-radio__background > .mdc-radio__outer-circle {
  border-color: var(--mat-radio-selected-focus-icon-color, var(--mat-sys-primary));
}
.mat-mdc-radio-button .mdc-radio__native-control:enabled:focus:checked + .mdc-radio__background > .mdc-radio__inner-circle {
  background-color: var(--mat-radio-selected-focus-icon-color, var(--mat-sys-primary, currentColor));
}
.mat-mdc-radio-button .mdc-radio__native-control:checked + .mdc-radio__background > .mdc-radio__inner-circle {
  transform: scale(0.5);
  transition: transform 90ms cubic-bezier(0, 0, 0.2, 1), background-color 90ms cubic-bezier(0, 0, 0.2, 1);
}
.mat-mdc-radio-button.mat-mdc-radio-disabled-interactive .mdc-radio--disabled {
  pointer-events: auto;
}
.mat-mdc-radio-button.mat-mdc-radio-disabled-interactive .mdc-radio--disabled .mdc-radio__native-control:not(:checked) + .mdc-radio__background > .mdc-radio__outer-circle {
  border-color: var(--mat-radio-disabled-unselected-icon-color, var(--mat-sys-on-surface));
  opacity: var(--mat-radio-disabled-unselected-icon-opacity, 0.38);
}
.mat-mdc-radio-button.mat-mdc-radio-disabled-interactive .mdc-radio--disabled:hover .mdc-radio__native-control:checked + .mdc-radio__background > .mdc-radio__outer-circle,
.mat-mdc-radio-button.mat-mdc-radio-disabled-interactive .mdc-radio--disabled .mdc-radio__native-control:checked:focus + .mdc-radio__background > .mdc-radio__outer-circle,
.mat-mdc-radio-button.mat-mdc-radio-disabled-interactive .mdc-radio--disabled .mdc-radio__native-control + .mdc-radio__background > .mdc-radio__outer-circle {
  border-color: var(--mat-radio-disabled-selected-icon-color, var(--mat-sys-on-surface));
  opacity: var(--mat-radio-disabled-selected-icon-opacity, 0.38);
}
.mat-mdc-radio-button.mat-mdc-radio-disabled-interactive .mdc-radio--disabled:hover .mdc-radio__native-control:checked + .mdc-radio__background > .mdc-radio__inner-circle,
.mat-mdc-radio-button.mat-mdc-radio-disabled-interactive .mdc-radio--disabled .mdc-radio__native-control:checked:focus + .mdc-radio__background > .mdc-radio__inner-circle,
.mat-mdc-radio-button.mat-mdc-radio-disabled-interactive .mdc-radio--disabled .mdc-radio__native-control + .mdc-radio__background > .mdc-radio__inner-circle {
  background-color: var(--mat-radio-disabled-selected-icon-color, var(--mat-sys-on-surface, currentColor));
  opacity: var(--mat-radio-disabled-selected-icon-opacity, 0.38);
}
.mat-mdc-radio-button._mat-animation-noopable .mdc-radio__background::before,
.mat-mdc-radio-button._mat-animation-noopable .mdc-radio__outer-circle,
.mat-mdc-radio-button._mat-animation-noopable .mdc-radio__inner-circle {
  transition: none !important;
}
.mat-mdc-radio-button label {
  cursor: pointer;
}
.mat-mdc-radio-button label:empty {
  display: none;
}
.mat-mdc-radio-button .mdc-radio__background::before {
  background-color: var(--mat-radio-ripple-color, var(--mat-sys-on-surface));
}
.mat-mdc-radio-button.mat-mdc-radio-checked .mat-ripple-element,
.mat-mdc-radio-button.mat-mdc-radio-checked .mdc-radio__background::before {
  background-color: var(--mat-radio-checked-ripple-color, var(--mat-sys-primary));
}
.mat-mdc-radio-button.mat-mdc-radio-disabled-interactive .mdc-radio--disabled .mat-ripple-element,
.mat-mdc-radio-button.mat-mdc-radio-disabled-interactive .mdc-radio--disabled .mdc-radio__background::before {
  background-color: var(--mat-radio-ripple-color, var(--mat-sys-on-surface));
}
.mat-mdc-radio-button .mat-internal-form-field {
  color: var(--mat-radio-label-text-color, var(--mat-sys-on-surface));
  font-family: var(--mat-radio-label-text-font, var(--mat-sys-body-medium-font));
  line-height: var(--mat-radio-label-text-line-height, var(--mat-sys-body-medium-line-height));
  font-size: var(--mat-radio-label-text-size, var(--mat-sys-body-medium-size));
  letter-spacing: var(--mat-radio-label-text-tracking, var(--mat-sys-body-medium-tracking));
  font-weight: var(--mat-radio-label-text-weight, var(--mat-sys-body-medium-weight));
}
.mat-mdc-radio-button .mdc-radio--disabled + label {
  color: var(--mat-radio-disabled-label-color, color-mix(in srgb, var(--mat-sys-on-surface) 38%, transparent));
}
.mat-mdc-radio-button .mat-radio-ripple {
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  position: absolute;
  pointer-events: none;
  border-radius: 50%;
}
.mat-mdc-radio-button .mat-radio-ripple > .mat-ripple-element {
  opacity: 0.14;
}
.mat-mdc-radio-button .mat-radio-ripple::before {
  border-radius: 50%;
}
.mat-mdc-radio-button .mdc-radio > .mdc-radio__native-control:focus:enabled:not(:checked) ~ .mdc-radio__background > .mdc-radio__outer-circle {
  border-color: var(--mat-radio-unselected-focus-icon-color, var(--mat-sys-on-surface));
}
.mat-mdc-radio-button.cdk-focused .mat-focus-indicator::before {
  content: "";
}

.mat-mdc-radio-disabled {
  cursor: default;
  pointer-events: none;
}
.mat-mdc-radio-disabled.mat-mdc-radio-disabled-interactive {
  pointer-events: auto;
}

.mat-mdc-radio-touch-target {
  position: absolute;
  top: 50%;
  left: 50%;
  height: var(--mat-radio-touch-target-size, 48px);
  width: var(--mat-radio-touch-target-size, 48px);
  transform: translate(-50%, -50%);
  display: var(--mat-radio-touch-target-display, block);
}
[dir=rtl] .mat-mdc-radio-touch-target {
  left: auto;
  right: 50%;
  transform: translate(50%, -50%);
}
`],encapsulation:2})}return n})();var wt=["*"];function Ce(n,r){n&1&&eD(0);}var Te=["tabListContainer"],xe=["tabList"],Ie=["tabListInner"],we=["nextPaginator"],De=["previousPaginator"],Re=["content"];function Ee(n,r){}var Me=["tabBodyWrapper"],Pe=["tabHeader"];function Be(n,r){}function Se(n,r){if(n&1&&xp(0,Be,0,0,"ng-template",12),n&2){let t=KE().$implicit;Fp("cdkPortalOutlet",t.templateLabel);}}function Le(n,r){if(n&1&&MD(0),n&2){let t=KE().$implicit;sh(t.textLabel);}}function Ae(n,r){if(n&1){let t=$E();hi(0,"div",7,2),qp("click",function(){let a=Tu(t),i=a.$implicit,s=a.$index,f=KE(),v=iD(1);return Cu(f._handleClick(i,v,s))})("cdkFocusChange",function(a){let i=Tu(t).$index,s=KE();return Cu(s._tabFocusChanged(a,i))}),jp(2,"span",8)(3,"div",9),hi(4,"span",10)(5,"span",11),AE(6,Se,1,1,null,12)(7,Le,1,1),kc()()();}if(n&2){let t=r.$implicit,e=r.$index,a=iD(1),i=KE();mD(t.labelClass),Xp("mdc-tab--active",i.selectedIndex===e),Fp("id",i._getTabLabelId(t,e))("disabled",t.disabled)("fitInkBarToContent",i.fitInkBarToContent),Lp("tabIndex",i._getTabIndex(e))("aria-posinset",e+1)("aria-setsize",i._tabs.length)("aria-controls",i._getTabContentId(e))("aria-selected",i.selectedIndex===e)("aria-label",t.ariaLabel||null)("aria-labelledby",!t.ariaLabel&&t.ariaLabelledby?t.ariaLabelledby:null),_v(3),Fp("matRippleTrigger",a)("matRippleDisabled",t.disabled||i.disableRipple),_v(3),RE(t.templateLabel?6:7);}}function Fe(n,r){n&1&&eD(0);}function Oe(n,r){if(n&1){let t=$E();hi(0,"mat-tab-body",13),qp("_onCentered",function(){Tu(t);let a=KE();return Cu(a._removeTabBodyWrapperHeight())})("_onCentering",function(a){Tu(t);let i=KE();return Cu(i._setTabBodyWrapperHeight(a))})("_beforeCentering",function(a){Tu(t);let i=KE();return Cu(i._bodyCentered(a))}),kc();}if(n&2){let t=r.$implicit,e=r.$index,a=KE();mD(t.bodyClass),Fp("id",a._getTabContentId(e))("content",t.content)("position",t.position)("animationDuration",a._bodyAnimationDuration)("preserveContent",a.preserveContent),Lp("tabindex",a.contentTabIndex!=null&&a.selectedIndex===e?a.contentTabIndex:null)("aria-labelledby",a._getTabLabelId(t,e))("aria-hidden",a.selectedIndex!==e);}}var ze=new N("MatTabContent"),He=(()=>{class n{template=T(dr);static \u0275fac=function(e){return new(e||n)};static \u0275dir=dE({type:n,selectors:[["","matTabContent",""]],features:[LD([{provide:ze,useExisting:n}])]})}return n})(),Ne=new N("MatTabLabel"),se=new N("MAT_TAB"),Ge=(()=>{class n extends _t{_closestTab=T(se,{optional:true});static \u0275fac=(()=>{let t;return function(a){return (t||(t=zm(n)))(a||n)}})();static \u0275dir=dE({type:n,selectors:[["","mat-tab-label",""],["","matTabLabel",""]],features:[LD([{provide:Ne,useExisting:n}]),Sp]})}return n})(),de=new N("MAT_TAB_GROUP"),Ve=(()=>{class n{_viewContainerRef=T(Ni);_closestTabGroup=T(de,{optional:true});disabled=false;get templateLabel(){return this._templateLabel}set templateLabel(t){this._setTemplateLabelInput(t);}_templateLabel;_explicitContent=void 0;_implicitContent;textLabel="";ariaLabel;ariaLabelledby;labelClass;bodyClass;id=null;_contentPortal=null;get content(){return this._contentPortal}_stateChanges=new te;position=null;origin=null;isActive=false;constructor(){T(F).load(oo);}ngOnChanges(t){(t.hasOwnProperty("textLabel")||t.hasOwnProperty("disabled"))&&this._stateChanges.next();}ngOnDestroy(){this._stateChanges.complete();}ngOnInit(){this._contentPortal=new E(this._explicitContent||this._implicitContent,this._viewContainerRef);}_setTemplateLabelInput(t){t&&t._closestTab===this&&(this._templateLabel=t);}static \u0275fac=function(e){return new(e||n)};static \u0275cmp=sE({type:n,selectors:[["mat-tab"]],contentQueries:function(e,a,i){if(e&1&&Gp(i,Ge,5)(i,He,7,dr),e&2){let s;nD(s=rD())&&(a.templateLabel=s.first),nD(s=rD())&&(a._explicitContent=s.first);}},viewQuery:function(e,a){if(e&1&&zp(dr,7),e&2){let i;nD(i=rD())&&(a._implicitContent=i.first);}},hostAttrs:["hidden",""],hostVars:1,hostBindings:function(e,a){e&2&&Lp("id",null);},inputs:{disabled:[2,"disabled","disabled",JF],textLabel:[0,"label","textLabel"],ariaLabel:[0,"aria-label","ariaLabel"],ariaLabelledby:[0,"aria-labelledby","ariaLabelledby"],labelClass:"labelClass",bodyClass:"bodyClass",id:"id"},exportAs:["matTab"],features:[LD([{provide:se,useExisting:n}]),Sm],ngContentSelectors:wt,decls:1,vars:0,template:function(e,a){e&1&&(XE(),Ap(0,Ce,1,0,"ng-template"));},encapsulation:2,changeDetection:1})}return n})(),kt="mdc-tab-indicator--active",ie="mdc-tab-indicator--no-transition",Tt=class{_items;_currentItem;constructor(r){this._items=r;}hide(){this._items.forEach(r=>r.deactivateInkBar()),this._currentItem=void 0;}alignToElement(r){let t=this._items.find(a=>a.elementRef.nativeElement===r),e=this._currentItem;if(t!==e&&(e?.deactivateInkBar(),t)){let a=e?.elementRef.nativeElement.getBoundingClientRect?.();t.activateInkBar(a),this._currentItem=t;}}},Qe=(()=>{class n{_elementRef=T(vr);_inkBarElement=null;_inkBarContentElement=null;_fitToContent=false;get fitInkBarToContent(){return this._fitToContent}set fitInkBarToContent(t){this._fitToContent!==t&&(this._fitToContent=t,this._inkBarElement&&this._appendInkBarElement());}activateInkBar(t){let e=this._elementRef.nativeElement;if(!t||!e.getBoundingClientRect||!this._inkBarContentElement){e.classList.add(kt);return}let a=e.getBoundingClientRect(),i=t.width/a.width,s=t.left-a.left;e.classList.add(ie),this._inkBarContentElement.style.setProperty("transform",`translateX(${s}px) scaleX(${i})`),e.getBoundingClientRect(),e.classList.remove(ie),e.classList.add(kt),this._inkBarContentElement.style.setProperty("transform","");}deactivateInkBar(){this._elementRef.nativeElement.classList.remove(kt);}ngOnInit(){this._createInkBarElement();}ngOnDestroy(){this._inkBarElement?.remove(),this._inkBarElement=this._inkBarContentElement=null;}_createInkBarElement(){let t=this._elementRef.nativeElement.ownerDocument||document,e=this._inkBarElement=t.createElement("span"),a=this._inkBarContentElement=t.createElement("span");e.className="mdc-tab-indicator",a.className="mdc-tab-indicator__content mdc-tab-indicator__content--underline",e.appendChild(this._inkBarContentElement),this._appendInkBarElement();}_appendInkBarElement(){this._inkBarElement;let t=this._fitToContent?this._elementRef.nativeElement.querySelector(".mdc-tab__content"):this._elementRef.nativeElement;t.appendChild(this._inkBarElement);}static \u0275fac=function(e){return new(e||n)};static \u0275dir=dE({type:n,inputs:{fitInkBarToContent:[2,"fitInkBarToContent","fitInkBarToContent",JF]}})}return n})();var ce=(()=>{class n extends Qe{elementRef=T(vr);disabled=false;focus(){this.elementRef.nativeElement.focus();}getOffsetLeft(){return this.elementRef.nativeElement.offsetLeft}getOffsetWidth(){return this.elementRef.nativeElement.offsetWidth}static \u0275fac=(()=>{let t;return function(a){return (t||(t=zm(n)))(a||n)}})();static \u0275dir=dE({type:n,selectors:[["","matTabLabelWrapper",""]],hostVars:3,hostBindings:function(e,a){e&2&&(Lp("aria-disabled",!!a.disabled),Xp("mat-mdc-tab-disabled",a.disabled));},inputs:{disabled:[2,"disabled","disabled",JF]},features:[Sp]})}return n})(),re={passive:true},qe=650,je=100;function Ct(n){let r=n+"";return /^[0-9]+(?:\.[0-9]+)?$/.test(r)?`${n}ms`:/^[0-9]+(?:\.[0-9]+)?(?:ms|s)$/.test(r)?r:""}var We=(()=>{class n{_elementRef=T(vr);_changeDetectorRef=T(YF);_viewportRuler=T(st);_dir=T(Er,{optional:true});_ngZone=T(Me$1);_platform=T(_);_sharedResizeObserver=T(gi);_injector=T(pe$1);_renderer=T(lI);_animationsDisabled=nt$1();_eventCleanups;_scrollDistance=0;_selectedIndexChanged=false;_destroyed=new te;_showPaginationControls=false;_disableScrollAfter=true;_disableScrollBefore=true;_tabLabelCount;_scrollDistanceChanged=false;_keyManager;_currentTextContent;_stopScrolling=new te;disablePagination=false;get selectedIndex(){return this._selectedIndex}set selectedIndex(t){let e=isNaN(t)?0:t;this._selectedIndex!=e&&(this._selectedIndexChanged=true,this._selectedIndex=e,this._keyManager&&this._keyManager.updateActiveItem(e));}_selectedIndex=0;selectFocusedIndex=new He$1;indexFocused=new He$1;constructor(){this._eventCleanups=this._ngZone.runOutsideAngular(()=>[this._renderer.listen(this._elementRef.nativeElement,"mouseleave",()=>this._stopInterval())]);}ngAfterViewInit(){this._eventCleanups.push(this._renderer.listen(this._previousPaginator.nativeElement,"touchstart",()=>this._handlePaginatorPress("before"),re),this._renderer.listen(this._nextPaginator.nativeElement,"touchstart",()=>this._handlePaginatorPress("after"),re));}ngAfterContentInit(){let t=this._dir?this._dir.change:Bh("ltr"),e=this._sharedResizeObserver.observe(this._elementRef.nativeElement).pipe(vl(32),Dg(this._destroyed)),a=this._viewportRuler.change(150).pipe(Dg(this._destroyed)),i=()=>{this.updatePagination(),this._alignInkBarToSelectedTab();};this._keyManager=new _e$1(this._items).withHorizontalOrientation(this._getLayoutDirection()).withHomeAndEnd().withWrap().skipPredicate(()=>false),this._keyManager.updateActiveItem(Math.max(this._selectedIndex,0)),nv(i,{injector:this._injector}),ug(t,a,e,this._items.changes,this._itemsResized()).pipe(Dg(this._destroyed)).subscribe(()=>{this._ngZone.run(()=>{Promise.resolve().then(()=>{this._scrollDistance=Math.max(0,Math.min(this._getMaxScrollDistance(),this._scrollDistance)),i();});}),this._keyManager?.withHorizontalOrientation(this._getLayoutDirection());}),this._keyManager.change.subscribe(s=>{this.indexFocused.emit(s),this._setTabFocus(s);});}_itemsResized(){return typeof ResizeObserver!="function"?It$1:this._items.changes.pipe(Eg(this._items),El(t=>new M(e=>this._ngZone.runOutsideAngular(()=>{let a=new ResizeObserver(i=>e.next(i));return t.forEach(i=>a.observe(i.elementRef.nativeElement)),()=>{a.disconnect();}}))),Ig(1),Gt(t=>t.some(e=>e.contentRect.width>0&&e.contentRect.height>0)))}ngAfterContentChecked(){this._tabLabelCount!=this._items.length&&(this.updatePagination(),this._tabLabelCount=this._items.length,this._changeDetectorRef.markForCheck()),this._selectedIndexChanged&&(this._scrollToLabel(this._selectedIndex),this._checkScrollingControls(),this._alignInkBarToSelectedTab(),this._selectedIndexChanged=false,this._changeDetectorRef.markForCheck()),this._scrollDistanceChanged&&(this._updateTabScrollPosition(),this._scrollDistanceChanged=false,this._changeDetectorRef.markForCheck());}ngOnDestroy(){this._eventCleanups.forEach(t=>t()),this._keyManager?.destroy(),this._destroyed.next(),this._destroyed.complete(),this._stopScrolling.complete();}_handleKeydown(t){if(!Kn(t))switch(t.keyCode){case 13:case 32:if(this.focusIndex!==this.selectedIndex){let e=this._items.get(this.focusIndex);e&&!e.disabled&&(this.selectFocusedIndex.emit(this.focusIndex),this._itemSelected(t));}break;default:this._keyManager?.onKeydown(t);}}_onContentChanges(){let t=this._elementRef.nativeElement.textContent;t!==this._currentTextContent&&(this._currentTextContent=t||"",this._ngZone.run(()=>{this.updatePagination(),this._alignInkBarToSelectedTab(),this._changeDetectorRef.markForCheck();}));}updatePagination(){this._checkPaginationEnabled(),this._checkScrollingControls(),this._updateTabScrollPosition();}get focusIndex(){return this._keyManager?this._keyManager.activeItemIndex:0}set focusIndex(t){!this._isValidIndex(t)||this.focusIndex===t||!this._keyManager||this._keyManager.setActiveItem(t);}_isValidIndex(t){return this._items?!!this._items.toArray()[t]:true}_setTabFocus(t){if(this._showPaginationControls&&this._scrollToLabel(t),this._items&&this._items.length){this._items.toArray()[t].focus();let e=this._tabListContainer.nativeElement;this._getLayoutDirection()=="ltr"?e.scrollLeft=0:e.scrollLeft=e.scrollWidth-e.offsetWidth;}}_getLayoutDirection(){return this._dir&&this._dir.value==="rtl"?"rtl":"ltr"}_updateTabScrollPosition(){if(this.disablePagination)return;let t=this.scrollDistance,e=this._getLayoutDirection()==="ltr"?-t:t;this._tabList.nativeElement.style.transform=`translateX(${Math.round(e)}px)`,(this._platform.TRIDENT||this._platform.EDGE)&&(this._tabListContainer.nativeElement.scrollLeft=0);}get scrollDistance(){return this._scrollDistance}set scrollDistance(t){this._scrollTo(t);}_scrollHeader(t){let e=this._tabListContainer.nativeElement.offsetWidth,a=(t=="before"?-1:1)*e/3;return this._scrollTo(this._scrollDistance+a)}_handlePaginatorClick(t){this._stopInterval(),this._scrollHeader(t);}_scrollToLabel(t){if(this.disablePagination)return;let e=this._items?this._items.toArray()[t]:null;if(!e)return;let a=this._tabListContainer.nativeElement.offsetWidth,{offsetLeft:i,offsetWidth:s}=e.elementRef.nativeElement,f,v;this._getLayoutDirection()=="ltr"?(f=i,v=f+s):(v=this._tabListInner.nativeElement.offsetWidth-i,f=v-s);let w=this.scrollDistance,E=this.scrollDistance+a;f<w?this.scrollDistance-=w-f:v>E&&(this.scrollDistance+=Math.min(v-E,f-w));}_checkPaginationEnabled(){if(this.disablePagination)this._showPaginationControls=false;else {let t=this._tabListInner.nativeElement.scrollWidth,e=this._elementRef.nativeElement.offsetWidth,a=t-e>=5;a||(this.scrollDistance=0),a!==this._showPaginationControls&&(this._showPaginationControls=a,this._changeDetectorRef.markForCheck());}}_checkScrollingControls(){this.disablePagination?this._disableScrollAfter=this._disableScrollBefore=true:(this._disableScrollBefore=this.scrollDistance==0,this._disableScrollAfter=this.scrollDistance==this._getMaxScrollDistance(),this._changeDetectorRef.markForCheck());}_getMaxScrollDistance(){let t=this._tabListInner.nativeElement.scrollWidth,e=this._tabListContainer.nativeElement.offsetWidth;return t-e||0}_alignInkBarToSelectedTab(){let t=this._items&&this._items.length?this._items.toArray()[this.selectedIndex]:null,e=t?t.elementRef.nativeElement:null;e?this._inkBar.alignToElement(e):this._inkBar.hide();}_stopInterval(){this._stopScrolling.next();}_handlePaginatorPress(t,e){e&&e.button!=null&&e.button!==0||(this._stopInterval(),Pn(qe,je).pipe(Dg(ug(this._stopScrolling,this._destroyed))).subscribe(()=>{let{maxScrollDistance:a,distance:i}=this._scrollHeader(t);(i===0||i>=a)&&this._stopInterval();}));}_scrollTo(t){if(this.disablePagination)return {maxScrollDistance:0,distance:0};let e=this._getMaxScrollDistance();return this._scrollDistance=Math.max(0,Math.min(e,t)),this._scrollDistanceChanged=true,this._checkScrollingControls(),{maxScrollDistance:e,distance:this._scrollDistance}}static \u0275fac=function(e){return new(e||n)};static \u0275dir=dE({type:n,inputs:{disablePagination:[2,"disablePagination","disablePagination",JF],selectedIndex:[2,"selectedIndex","selectedIndex",XF]},outputs:{selectFocusedIndex:"selectFocusedIndex",indexFocused:"indexFocused"}})}return n})(),$e=(()=>{class n extends We{_items;_tabListContainer;_tabList;_tabListInner;_nextPaginator;_previousPaginator;_inkBar;ariaLabel;ariaLabelledby;disableRipple=false;ngAfterContentInit(){this._inkBar=new Tt(this._items),super.ngAfterContentInit();}_itemSelected(t){t.preventDefault();}static \u0275fac=(()=>{let t;return function(a){return (t||(t=zm(n)))(a||n)}})();static \u0275cmp=sE({type:n,selectors:[["mat-tab-header"]],contentQueries:function(e,a,i){if(e&1&&Gp(i,ce,4),e&2){let s;nD(s=rD())&&(a._items=s);}},viewQuery:function(e,a){if(e&1&&zp(Te,7)(xe,7)(Ie,7)(we,5)(De,5),e&2){let i;nD(i=rD())&&(a._tabListContainer=i.first),nD(i=rD())&&(a._tabList=i.first),nD(i=rD())&&(a._tabListInner=i.first),nD(i=rD())&&(a._nextPaginator=i.first),nD(i=rD())&&(a._previousPaginator=i.first);}},hostAttrs:[1,"mat-mdc-tab-header"],hostVars:4,hostBindings:function(e,a){e&2&&Xp("mat-mdc-tab-header-pagination-controls-enabled",a._showPaginationControls)("mat-mdc-tab-header-rtl",a._getLayoutDirection()=="rtl");},inputs:{ariaLabel:[0,"aria-label","ariaLabel"],ariaLabelledby:[0,"aria-labelledby","ariaLabelledby"],disableRipple:[2,"disableRipple","disableRipple",JF]},features:[Sp],ngContentSelectors:wt,decls:13,vars:10,consts:[["previousPaginator",""],["tabListContainer",""],["tabList",""],["tabListInner",""],["nextPaginator",""],["mat-ripple","",1,"mat-mdc-tab-header-pagination","mat-mdc-tab-header-pagination-before",3,"click","mousedown","touchend","matRippleDisabled"],[1,"mat-mdc-tab-header-pagination-chevron"],[1,"mat-mdc-tab-label-container",3,"keydown"],["role","tablist",1,"mat-mdc-tab-list",3,"cdkObserveContent"],[1,"mat-mdc-tab-labels"],["mat-ripple","",1,"mat-mdc-tab-header-pagination","mat-mdc-tab-header-pagination-after",3,"mousedown","click","touchend","matRippleDisabled"]],template:function(e,a){e&1&&(XE(),hi(0,"div",5,0),qp("click",function(){return a._handlePaginatorClick("before")})("mousedown",function(s){return a._handlePaginatorPress("before",s)})("touchend",function(){return a._stopInterval()}),jp(2,"div",6),kc(),hi(3,"div",7,1),qp("keydown",function(s){return a._handleKeydown(s)}),hi(5,"div",8,2),qp("cdkObserveContent",function(){return a._onContentChanges()}),hi(7,"div",9,3),eD(9),kc()()(),hi(10,"div",10,4),qp("mousedown",function(s){return a._handlePaginatorPress("after",s)})("click",function(){return a._handlePaginatorClick("after")})("touchend",function(){return a._stopInterval()}),jp(12,"div",6),kc()),e&2&&(Xp("mat-mdc-tab-header-pagination-disabled",a._disableScrollBefore),Fp("matRippleDisabled",a._disableScrollBefore||a.disableRipple),_v(3),Xp("_mat-animation-noopable",a._animationsDisabled),_v(2),Lp("aria-label",a.ariaLabel||null)("aria-labelledby",a.ariaLabelledby||null),_v(5),Xp("mat-mdc-tab-header-pagination-disabled",a._disableScrollAfter),Fp("matRippleDisabled",a._disableScrollAfter||a.disableRipple));},dependencies:[tc,hr],styles:[`.mat-mdc-tab-header {
  display: flex;
  overflow: hidden;
  position: relative;
  flex-shrink: 0;
}

.mdc-tab-indicator .mdc-tab-indicator__content {
  transition-duration: var(--mat-tab-header-animation-duration, 250ms);
}

.mat-mdc-tab-header-pagination {
  -webkit-user-select: none;
  user-select: none;
  position: relative;
  display: none;
  justify-content: center;
  align-items: center;
  min-width: 32px;
  cursor: pointer;
  z-index: 2;
  -webkit-tap-highlight-color: transparent;
  touch-action: none;
  box-sizing: content-box;
  outline: 0;
}
.mat-mdc-tab-header-pagination::-moz-focus-inner {
  border: 0;
}
.mat-mdc-tab-header-pagination .mat-ripple-element {
  opacity: 0.12;
  background-color: var(--mat-tab-inactive-ripple-color, var(--mat-sys-on-surface));
}
.mat-mdc-tab-header-pagination-controls-enabled .mat-mdc-tab-header-pagination {
  display: flex;
}

.mat-mdc-tab-header-pagination-before,
.mat-mdc-tab-header-rtl .mat-mdc-tab-header-pagination-after {
  padding-left: 4px;
}
.mat-mdc-tab-header-pagination-before .mat-mdc-tab-header-pagination-chevron,
.mat-mdc-tab-header-rtl .mat-mdc-tab-header-pagination-after .mat-mdc-tab-header-pagination-chevron {
  transform: rotate(-135deg);
}

.mat-mdc-tab-header-rtl .mat-mdc-tab-header-pagination-before,
.mat-mdc-tab-header-pagination-after {
  padding-right: 4px;
}
.mat-mdc-tab-header-rtl .mat-mdc-tab-header-pagination-before .mat-mdc-tab-header-pagination-chevron,
.mat-mdc-tab-header-pagination-after .mat-mdc-tab-header-pagination-chevron {
  transform: rotate(45deg);
}

.mat-mdc-tab-header-pagination-chevron {
  border-style: solid;
  border-width: 2px 2px 0 0;
  height: 8px;
  width: 8px;
  border-color: var(--mat-tab-pagination-icon-color, var(--mat-sys-on-surface));
}

.mat-mdc-tab-header-pagination-disabled {
  box-shadow: none;
  cursor: default;
  pointer-events: none;
}
.mat-mdc-tab-header-pagination-disabled .mat-mdc-tab-header-pagination-chevron {
  opacity: 0.4;
}

.mat-mdc-tab-list {
  flex-grow: 1;
  position: relative;
  transition: transform 500ms cubic-bezier(0.35, 0, 0.25, 1);
}
._mat-animation-noopable .mat-mdc-tab-list {
  transition: none;
}

.mat-mdc-tab-label-container {
  display: flex;
  flex-grow: 1;
  overflow: hidden;
  z-index: 1;
  border-bottom-style: solid;
  border-bottom-width: var(--mat-tab-divider-height, 1px);
  border-bottom-color: var(--mat-tab-divider-color, var(--mat-sys-surface-variant));
}
.mat-mdc-tab-group-inverted-header .mat-mdc-tab-label-container {
  border-bottom: none;
  border-top-style: solid;
  border-top-width: var(--mat-tab-divider-height, 1px);
  border-top-color: var(--mat-tab-divider-color, var(--mat-sys-surface-variant));
}

.mat-mdc-tab-labels {
  display: flex;
  flex: 1 0 auto;
}
[mat-align-tabs=center] > .mat-mdc-tab-header .mat-mdc-tab-labels {
  justify-content: center;
}
[mat-align-tabs=end] > .mat-mdc-tab-header .mat-mdc-tab-labels {
  justify-content: flex-end;
}
.cdk-drop-list .mat-mdc-tab-labels, .mat-mdc-tab-labels.cdk-drop-list {
  min-height: var(--mat-tab-container-height, 48px);
}

.mat-mdc-tab::before {
  margin: 5px;
}
@media (forced-colors: active) {
  .mat-mdc-tab[aria-disabled=true] {
    color: GrayText;
  }
}
`],encapsulation:2,changeDetection:1})}return n})(),Ze=new N("MAT_TABS_CONFIG"),oe=(()=>{class n extends gt$1{_host=T(xt);_ngZone=T(Me$1);_centeringSub=j.EMPTY;_leavingSub=j.EMPTY;ngOnInit(){super.ngOnInit(),this._centeringSub=this._host._beforeCentering.pipe(Eg(this._host._isCenterPosition())).subscribe(t=>{this._host._content&&t&&!this.hasAttached()&&this._ngZone.run(()=>{Promise.resolve().then(),this.attach(this._host._content);});}),this._leavingSub=this._host._afterLeavingCenter.subscribe(()=>{this._host.preserveContent||this._ngZone.run(()=>this.detach());});}ngOnDestroy(){super.ngOnDestroy(),this._centeringSub.unsubscribe(),this._leavingSub.unsubscribe();}static \u0275fac=(()=>{let t;return function(a){return (t||(t=zm(n)))(a||n)}})();static \u0275dir=dE({type:n,selectors:[["","matTabBodyHost",""]],features:[Sp]})}return n})(),xt=(()=>{class n{_elementRef=T(vr);_dir=T(Er,{optional:true});_ngZone=T(Me$1);_injector=T(pe$1);_renderer=T(lI);_diAnimationsDisabled=nt$1();_eventCleanups;_initialized=false;_fallbackTimer;_positionIndex;_dirChangeSubscription=j.EMPTY;_position;_previousPosition;_onCentering=new He$1;_beforeCentering=new He$1;_afterLeavingCenter=new He$1;_onCentered=new He$1(true);_portalHost;_contentElement;_content;animationDuration="500ms";preserveContent=false;set position(t){this._positionIndex=t,this._computePositionAnimationState();}constructor(){if(this._dir){let t=T(YF);this._dirChangeSubscription=this._dir.change.subscribe(e=>{this._computePositionAnimationState(e),t.markForCheck();});}}ngOnInit(){this._bindTransitionEvents(),this._position==="center"&&(this._setActiveClass(true),nv(()=>this._onCentering.emit(this._elementRef.nativeElement.clientHeight),{injector:this._injector})),this._initialized=true;}ngOnDestroy(){clearTimeout(this._fallbackTimer),this._eventCleanups?.forEach(t=>t()),this._dirChangeSubscription.unsubscribe();}_bindTransitionEvents(){this._ngZone.runOutsideAngular(()=>{let t=this._elementRef.nativeElement,e=a=>{a.target===this._contentElement?.nativeElement&&(this._elementRef.nativeElement.classList.remove("mat-tab-body-animating"),a.type==="transitionend"&&this._transitionDone());};this._eventCleanups=[this._renderer.listen(t,"transitionstart",a=>{a.target===this._contentElement?.nativeElement&&(this._elementRef.nativeElement.classList.add("mat-tab-body-animating"),this._transitionStarted());}),this._renderer.listen(t,"transitionend",e),this._renderer.listen(t,"transitioncancel",e)];});}_transitionStarted(){clearTimeout(this._fallbackTimer);let t=this._position==="center";this._beforeCentering.emit(t),t&&this._onCentering.emit(this._elementRef.nativeElement.clientHeight);}_transitionDone(){this._position==="center"?this._onCentered.emit():this._previousPosition==="center"&&this._afterLeavingCenter.emit();}_setActiveClass(t){this._elementRef.nativeElement.classList.toggle("mat-mdc-tab-body-active",t);}_getLayoutDirection(){return this._dir&&this._dir.value==="rtl"?"rtl":"ltr"}_isCenterPosition(){return this._positionIndex===0}_computePositionAnimationState(t=this._getLayoutDirection()){this._previousPosition=this._position,this._positionIndex<0?this._position=t=="ltr"?"left":"right":this._positionIndex>0?this._position=t=="ltr"?"right":"left":this._position="center",this._animationsDisabled()?this._simulateTransitionEvents():this._initialized&&(this._position==="center"||this._previousPosition==="center")&&(clearTimeout(this._fallbackTimer),this._fallbackTimer=this._ngZone.runOutsideAngular(()=>setTimeout(()=>this._simulateTransitionEvents(),100)));}_simulateTransitionEvents(){this._transitionStarted(),nv(()=>this._transitionDone(),{injector:this._injector});}_animationsDisabled(){return this._diAnimationsDisabled||this.animationDuration==="0ms"||this.animationDuration==="0s"}static \u0275fac=function(e){return new(e||n)};static \u0275cmp=sE({type:n,selectors:[["mat-tab-body"]],viewQuery:function(e,a){if(e&1&&zp(oe,5)(Re,5),e&2){let i;nD(i=rD())&&(a._portalHost=i.first),nD(i=rD())&&(a._contentElement=i.first);}},hostAttrs:[1,"mat-mdc-tab-body"],hostVars:1,hostBindings:function(e,a){e&2&&Lp("inert",a._position==="center"?null:"");},inputs:{_content:[0,"content","_content"],animationDuration:"animationDuration",preserveContent:"preserveContent",position:"position"},outputs:{_onCentering:"_onCentering",_beforeCentering:"_beforeCentering",_onCentered:"_onCentered"},decls:3,vars:6,consts:[["content",""],["cdkScrollable","",1,"mat-mdc-tab-body-content"],["matTabBodyHost",""]],template:function(e,a){e&1&&(hi(0,"div",1,0),xp(2,Ee,0,0,"ng-template",2),kc()),e&2&&Xp("mat-tab-body-content-left",a._position==="left")("mat-tab-body-content-right",a._position==="right")("mat-tab-body-content-can-animate",a._position==="center"||a._previousPosition==="center");},dependencies:[oe,ot],styles:[`.mat-mdc-tab-body {
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  position: absolute;
  display: block;
  overflow: hidden;
  outline: 0;
  flex-basis: 100%;
}
.mat-mdc-tab-body.mat-mdc-tab-body-active {
  position: relative;
  overflow-x: hidden;
  overflow-y: auto;
  z-index: 1;
  flex-grow: 1;
}
.mat-mdc-tab-group.mat-mdc-tab-group-dynamic-height .mat-mdc-tab-body.mat-mdc-tab-body-active {
  overflow-y: hidden;
}

.mat-mdc-tab-body-content {
  height: 100%;
  overflow: auto;
  transform: none;
  visibility: hidden;
}
.mat-tab-body-animating > .mat-mdc-tab-body-content, .mat-mdc-tab-body-active > .mat-mdc-tab-body-content {
  visibility: visible;
}
.mat-tab-body-animating > .mat-mdc-tab-body-content {
  min-height: 1px;
}
.mat-mdc-tab-group-dynamic-height .mat-mdc-tab-body-content {
  overflow: hidden;
}

.mat-tab-body-content-can-animate {
  transition: transform var(--mat-tab-body-animation-duration) 1ms cubic-bezier(0.35, 0, 0.25, 1);
}
.mat-mdc-tab-body-wrapper._mat-animation-noopable .mat-tab-body-content-can-animate {
  transition: none;
}

.mat-tab-body-content-left {
  transform: translate3d(-100%, 0, 0);
}

.mat-tab-body-content-right {
  transform: translate3d(100%, 0, 0);
}
`],encapsulation:2,changeDetection:1})}return n})(),en=(()=>{class n{_elementRef=T(vr);_changeDetectorRef=T(YF);_ngZone=T(Me$1);_tabsSubscription=j.EMPTY;_tabLabelSubscription=j.EMPTY;_tabBodySubscription=j.EMPTY;_diAnimationsDisabled=nt$1();_bodyAnimationDuration;_headerAnimationDuration;_allTabs;_tabBodies;_tabBodyWrapper;_tabHeader;_tabs=new ei;_indexToSelect=0;_lastFocusedTabIndex=null;_tabBodyWrapperHeight=0;color;get fitInkBarToContent(){return this._fitInkBarToContent}set fitInkBarToContent(t){this._fitInkBarToContent=t,this._changeDetectorRef.markForCheck();}_fitInkBarToContent=false;stretchTabs=true;alignTabs=null;dynamicHeight=false;get selectedIndex(){return this._selectedIndex}set selectedIndex(t){this._indexToSelect=isNaN(t)?null:t;}_selectedIndex=null;headerPosition="above";get animationDuration(){return this._animationDuration}set animationDuration(t){this._animationDuration=t,t&&typeof t=="object"?(this._bodyAnimationDuration=Ct(t.body),this._headerAnimationDuration=Ct(t.header)):this._headerAnimationDuration=this._bodyAnimationDuration=Ct(t);}_animationDuration;get contentTabIndex(){return this._contentTabIndex}set contentTabIndex(t){this._contentTabIndex=isNaN(t)?null:t;}_contentTabIndex=null;disablePagination=false;disableRipple=false;preserveContent=false;get backgroundColor(){return this._backgroundColor}set backgroundColor(t){let e=this._elementRef.nativeElement.classList;e.remove("mat-tabs-with-background",`mat-background-${this.backgroundColor}`),t&&e.add("mat-tabs-with-background",`mat-background-${t}`),this._backgroundColor=t;}_backgroundColor;ariaLabel;ariaLabelledby;selectedIndexChange=new He$1;focusChange=new He$1;animationDone=new He$1;selectedTabChange=new He$1(true);_groupId;_isServer=!T(_).isBrowser;constructor(){let t=T(Ze,{optional:true});this._groupId=T(ye).getId("mat-tab-group-"),this.animationDuration=t&&t.animationDuration?t.animationDuration:"500ms",this.disablePagination=t&&t.disablePagination!=null?t.disablePagination:false,this.dynamicHeight=t&&t.dynamicHeight!=null?t.dynamicHeight:false,t?.contentTabIndex!=null&&(this.contentTabIndex=t.contentTabIndex),this.preserveContent=!!t?.preserveContent,this.fitInkBarToContent=t&&t.fitInkBarToContent!=null?t.fitInkBarToContent:false,this.stretchTabs=t&&t.stretchTabs!=null?t.stretchTabs:true,this.alignTabs=t&&t.alignTabs!=null?t.alignTabs:null;}ngAfterContentChecked(){let t=this._indexToSelect=this._clampTabIndex(this._indexToSelect);if(this._selectedIndex!=t){let e=this._selectedIndex==null;if(!e){this.selectedTabChange.emit(this._createChangeEvent(t));let a=this._tabBodyWrapper.nativeElement;a.style.minHeight=a.clientHeight+"px";}Promise.resolve().then(()=>{this._tabs.forEach((a,i)=>a.isActive=i===t),e||(this.selectedIndexChange.emit(t),this._tabBodyWrapper.nativeElement.style.minHeight="");});}this._tabs.forEach((e,a)=>{e.position=a-t,this._selectedIndex!=null&&e.position==0&&!e.origin&&(e.origin=t-this._selectedIndex);}),this._selectedIndex!==t&&(this._selectedIndex=t,this._lastFocusedTabIndex=null,this._changeDetectorRef.markForCheck());}ngAfterContentInit(){this._subscribeToAllTabChanges(),this._subscribeToTabLabels(),this._tabsSubscription=this._tabs.changes.subscribe(()=>{let t=this._clampTabIndex(this._indexToSelect);if(t===this._selectedIndex){let e=this._tabs.toArray(),a;for(let i=0;i<e.length;i++)if(e[i].isActive){this._indexToSelect=this._selectedIndex=i,this._lastFocusedTabIndex=null,a=e[i];break}!a&&e[t]&&Promise.resolve().then(()=>{e[t].isActive=true,this.selectedTabChange.emit(this._createChangeEvent(t));});}this._changeDetectorRef.markForCheck();});}ngAfterViewInit(){this._tabBodySubscription=this._tabBodies.changes.subscribe(()=>this._bodyCentered(true));}_subscribeToAllTabChanges(){this._allTabs.changes.pipe(Eg(this._allTabs)).subscribe(t=>{this._tabs.reset(t.filter(e=>e._closestTabGroup===this||!e._closestTabGroup)),this._tabs.notifyOnChanges();});}ngOnDestroy(){this._tabs.destroy(),this._tabsSubscription.unsubscribe(),this._tabLabelSubscription.unsubscribe(),this._tabBodySubscription.unsubscribe();}realignInkBar(){this._tabHeader&&this._tabHeader._alignInkBarToSelectedTab();}updatePagination(){this._tabHeader&&this._tabHeader.updatePagination();}focusTab(t){let e=this._tabHeader;e&&(e.focusIndex=t);}_focusChanged(t){this._lastFocusedTabIndex=t,this.focusChange.emit(this._createChangeEvent(t));}_createChangeEvent(t){let e=new It;return e.index=t,this._tabs&&this._tabs.length&&(e.tab=this._tabs.toArray()[t]),e}_subscribeToTabLabels(){this._tabLabelSubscription&&this._tabLabelSubscription.unsubscribe(),this._tabLabelSubscription=ug(...this._tabs.map(t=>t._stateChanges)).subscribe(()=>this._changeDetectorRef.markForCheck());}_clampTabIndex(t){return Math.min(this._tabs.length-1,Math.max(t||0,0))}_getTabLabelId(t,e){return t.id||`${this._groupId}-label-${e}`}_getTabContentId(t){return `${this._groupId}-content-${t}`}_setTabBodyWrapperHeight(t){if(!this.dynamicHeight||!this._tabBodyWrapperHeight){this._tabBodyWrapperHeight=t;return}let e=this._tabBodyWrapper.nativeElement;e.style.height=this._tabBodyWrapperHeight+"px",this._tabBodyWrapper.nativeElement.offsetHeight&&(e.style.height=t+"px");}_removeTabBodyWrapperHeight(){let t=this._tabBodyWrapper.nativeElement;this._tabBodyWrapperHeight=t.clientHeight,t.style.height="",this._ngZone.run(()=>this.animationDone.emit());}_handleClick(t,e,a){e.focusIndex=a,t.disabled||(this.selectedIndex=a);}_getTabIndex(t){let e=this._lastFocusedTabIndex??this.selectedIndex;return t===e?0:-1}_tabFocusChanged(t,e){t&&t!=="mouse"&&t!=="touch"&&(this._tabHeader.focusIndex=e);}_bodyCentered(t){t&&this._tabBodies?.forEach((e,a)=>e._setActiveClass(a===this._selectedIndex));}_bodyAnimationsDisabled(){return this._diAnimationsDisabled||this._bodyAnimationDuration==="0"||this._bodyAnimationDuration==="0ms"}static \u0275fac=function(e){return new(e||n)};static \u0275cmp=sE({type:n,selectors:[["mat-tab-group"]],contentQueries:function(e,a,i){if(e&1&&Gp(i,Ve,5),e&2){let s;nD(s=rD())&&(a._allTabs=s);}},viewQuery:function(e,a){if(e&1&&zp(Me,5)(Pe,5)(xt,5),e&2){let i;nD(i=rD())&&(a._tabBodyWrapper=i.first),nD(i=rD())&&(a._tabHeader=i.first),nD(i=rD())&&(a._tabBodies=i);}},hostAttrs:[1,"mat-mdc-tab-group"],hostVars:13,hostBindings:function(e,a){e&2&&(Lp("mat-align-tabs",a.alignTabs),mD("mat-"+(a.color||"primary")),Jp("--mat-tab-body-animation-duration",a._bodyAnimationDuration)("--mat-tab-header-animation-duration",a._headerAnimationDuration),Xp("mat-mdc-tab-group-dynamic-height",a.dynamicHeight)("mat-mdc-tab-group-inverted-header",a.headerPosition==="below")("mat-mdc-tab-group-stretch-tabs",a.stretchTabs));},inputs:{color:"color",fitInkBarToContent:[2,"fitInkBarToContent","fitInkBarToContent",JF],stretchTabs:[2,"mat-stretch-tabs","stretchTabs",JF],alignTabs:[0,"mat-align-tabs","alignTabs"],dynamicHeight:[2,"dynamicHeight","dynamicHeight",JF],selectedIndex:[2,"selectedIndex","selectedIndex",XF],headerPosition:"headerPosition",animationDuration:"animationDuration",contentTabIndex:[2,"contentTabIndex","contentTabIndex",XF],disablePagination:[2,"disablePagination","disablePagination",JF],disableRipple:[2,"disableRipple","disableRipple",JF],preserveContent:[2,"preserveContent","preserveContent",JF],backgroundColor:"backgroundColor",ariaLabel:[0,"aria-label","ariaLabel"],ariaLabelledby:[0,"aria-labelledby","ariaLabelledby"]},outputs:{selectedIndexChange:"selectedIndexChange",focusChange:"focusChange",animationDone:"animationDone",selectedTabChange:"selectedTabChange"},exportAs:["matTabGroup"],features:[LD([{provide:de,useExisting:n}])],ngContentSelectors:wt,decls:9,vars:8,consts:[["tabHeader",""],["tabBodyWrapper",""],["tabNode",""],[3,"indexFocused","selectFocusedIndex","selectedIndex","disableRipple","disablePagination","aria-label","aria-labelledby"],["role","tab","matTabLabelWrapper","","cdkMonitorElementFocus","",1,"mdc-tab","mat-mdc-tab","mat-focus-indicator",3,"id","mdc-tab--active","class","disabled","fitInkBarToContent"],[1,"mat-mdc-tab-body-wrapper"],["role","tabpanel",3,"id","class","content","position","animationDuration","preserveContent"],["role","tab","matTabLabelWrapper","","cdkMonitorElementFocus","",1,"mdc-tab","mat-mdc-tab","mat-focus-indicator",3,"click","cdkFocusChange","id","disabled","fitInkBarToContent"],[1,"mdc-tab__ripple"],["mat-ripple","",1,"mat-mdc-tab-ripple",3,"matRippleTrigger","matRippleDisabled"],[1,"mdc-tab__content"],[1,"mdc-tab__text-label"],[3,"cdkPortalOutlet"],["role","tabpanel",3,"_onCentered","_onCentering","_beforeCentering","id","content","position","animationDuration","preserveContent"]],template:function(e,a){e&1&&(XE(),hi(0,"mat-tab-header",3,0),qp("indexFocused",function(s){return a._focusChanged(s)})("selectFocusedIndex",function(s){return a.selectedIndex=s}),PE(2,Ae,8,17,"div",4,OE),kc(),AE(4,Fe,1,0),hi(5,"div",5,1),PE(7,Oe,1,10,"mat-tab-body",6,OE),kc()),e&2&&(Fp("selectedIndex",a.selectedIndex||0)("disableRipple",a.disableRipple)("disablePagination",a.disablePagination),Pp("aria-label",a.ariaLabel)("aria-labelledby",a.ariaLabelledby),_v(2),LE(a._tabs),_v(2),RE(a._isServer?4:-1),_v(),Xp("_mat-animation-noopable",a._bodyAnimationsDisabled()),_v(2),LE(a._tabs));},dependencies:[$e,ce,Co,tc,gt$1,xt],styles:[`.mdc-tab {
  min-width: 90px;
  padding: 0 24px;
  display: flex;
  flex: 1 0 auto;
  justify-content: center;
  box-sizing: border-box;
  border: none;
  outline: none;
  text-align: center;
  white-space: nowrap;
  cursor: pointer;
  z-index: 1;
  touch-action: manipulation;
}

.mdc-tab__content {
  display: flex;
  align-items: center;
  justify-content: center;
  height: inherit;
  pointer-events: none;
}

.mdc-tab__text-label {
  transition: 150ms color linear;
  display: inline-block;
  line-height: 1;
  z-index: 2;
}

.mdc-tab--active .mdc-tab__text-label {
  transition-delay: 100ms;
}

._mat-animation-noopable .mdc-tab__text-label {
  transition: none;
}

.mdc-tab-indicator {
  display: flex;
  position: absolute;
  top: 0;
  left: 0;
  justify-content: center;
  width: 100%;
  height: 100%;
  pointer-events: none;
  z-index: 1;
}

.mdc-tab-indicator__content {
  transition: var(--mat-tab-header-animation-duration, 250ms) transform cubic-bezier(0.4, 0, 0.2, 1);
  transform-origin: left;
  opacity: 0;
}

.mdc-tab-indicator__content--underline {
  align-self: flex-end;
  box-sizing: border-box;
  width: 100%;
  border-top-style: solid;
}

.mdc-tab-indicator--active .mdc-tab-indicator__content {
  opacity: 1;
}

._mat-animation-noopable .mdc-tab-indicator__content, .mdc-tab-indicator--no-transition .mdc-tab-indicator__content {
  transition: none;
}

.mat-mdc-tab-ripple.mat-mdc-tab-ripple {
  position: absolute;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
  pointer-events: none;
}

.mat-mdc-tab {
  -webkit-tap-highlight-color: transparent;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-decoration: none;
  background: none;
  height: var(--mat-tab-container-height, 48px);
  font-family: var(--mat-tab-label-text-font, var(--mat-sys-title-small-font));
  font-size: var(--mat-tab-label-text-size, var(--mat-sys-title-small-size));
  letter-spacing: var(--mat-tab-label-text-tracking, var(--mat-sys-title-small-tracking));
  line-height: var(--mat-tab-label-text-line-height, var(--mat-sys-title-small-line-height));
  font-weight: var(--mat-tab-label-text-weight, var(--mat-sys-title-small-weight));
}
.mat-mdc-tab.mdc-tab {
  flex-grow: 0;
}
.mat-mdc-tab .mdc-tab-indicator__content--underline {
  border-color: var(--mat-tab-active-indicator-color, var(--mat-sys-primary));
  border-top-width: var(--mat-tab-active-indicator-height, 2px);
  border-radius: var(--mat-tab-active-indicator-shape, 0);
}
.mat-mdc-tab:hover .mdc-tab__text-label {
  color: var(--mat-tab-inactive-hover-label-text-color, var(--mat-sys-on-surface));
}
.mat-mdc-tab:focus .mdc-tab__text-label {
  color: var(--mat-tab-inactive-focus-label-text-color, var(--mat-sys-on-surface));
}
.mat-mdc-tab.mdc-tab--active .mdc-tab__text-label {
  color: var(--mat-tab-active-label-text-color, var(--mat-sys-on-surface));
}
.mat-mdc-tab.mdc-tab--active .mdc-tab__ripple::before,
.mat-mdc-tab.mdc-tab--active .mat-ripple-element {
  background-color: var(--mat-tab-active-ripple-color, var(--mat-sys-on-surface));
}
.mat-mdc-tab.mdc-tab--active:hover .mdc-tab__text-label {
  color: var(--mat-tab-active-hover-label-text-color, var(--mat-sys-on-surface));
}
.mat-mdc-tab.mdc-tab--active:hover .mdc-tab-indicator__content--underline {
  border-color: var(--mat-tab-active-hover-indicator-color, var(--mat-sys-primary));
}
.mat-mdc-tab.mdc-tab--active:focus .mdc-tab__text-label {
  color: var(--mat-tab-active-focus-label-text-color, var(--mat-sys-on-surface));
}
.mat-mdc-tab.mdc-tab--active:focus .mdc-tab-indicator__content--underline {
  border-color: var(--mat-tab-active-focus-indicator-color, var(--mat-sys-primary));
}
.mat-mdc-tab.mat-mdc-tab-disabled {
  opacity: 0.4;
  pointer-events: none;
}
.mat-mdc-tab.mat-mdc-tab-disabled .mdc-tab__content {
  pointer-events: none;
}
.mat-mdc-tab.mat-mdc-tab-disabled .mdc-tab__ripple::before,
.mat-mdc-tab.mat-mdc-tab-disabled .mat-ripple-element {
  background-color: var(--mat-tab-disabled-ripple-color, var(--mat-sys-on-surface-variant));
}
.mat-mdc-tab .mdc-tab__ripple::before {
  content: "";
  display: block;
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  opacity: 0;
  pointer-events: none;
  background-color: var(--mat-tab-inactive-ripple-color, var(--mat-sys-on-surface));
}
.mat-mdc-tab .mdc-tab__text-label {
  color: var(--mat-tab-inactive-label-text-color, var(--mat-sys-on-surface));
  display: inline-flex;
  align-items: center;
}
.mat-mdc-tab .mdc-tab__content {
  position: relative;
  pointer-events: auto;
}
.mat-mdc-tab:hover .mdc-tab__ripple::before {
  opacity: 0.04;
}
.mat-mdc-tab.cdk-program-focused .mdc-tab__ripple::before, .mat-mdc-tab.cdk-keyboard-focused .mdc-tab__ripple::before {
  opacity: 0.12;
}
.mat-mdc-tab .mat-ripple-element {
  opacity: 0.12;
  background-color: var(--mat-tab-inactive-ripple-color, var(--mat-sys-on-surface));
}
.mat-mdc-tab-group.mat-mdc-tab-group-stretch-tabs > .mat-mdc-tab-header .mat-mdc-tab {
  flex-grow: 1;
}

.mat-mdc-tab-group {
  display: flex;
  flex-direction: column;
  max-width: 100%;
}
.mat-mdc-tab-group.mat-tabs-with-background > .mat-mdc-tab-header, .mat-mdc-tab-group.mat-tabs-with-background > .mat-mdc-tab-header-pagination {
  background-color: var(--mat-tab-background-color);
}
.mat-mdc-tab-group.mat-tabs-with-background.mat-primary > .mat-mdc-tab-header .mat-mdc-tab .mdc-tab__text-label {
  color: var(--mat-tab-foreground-color);
}
.mat-mdc-tab-group.mat-tabs-with-background.mat-primary > .mat-mdc-tab-header .mdc-tab-indicator__content--underline {
  border-color: var(--mat-tab-foreground-color);
}
.mat-mdc-tab-group.mat-tabs-with-background:not(.mat-primary) > .mat-mdc-tab-header .mat-mdc-tab:not(.mdc-tab--active) .mdc-tab__text-label {
  color: var(--mat-tab-foreground-color);
}
.mat-mdc-tab-group.mat-tabs-with-background:not(.mat-primary) > .mat-mdc-tab-header .mat-mdc-tab:not(.mdc-tab--active) .mdc-tab-indicator__content--underline {
  border-color: var(--mat-tab-foreground-color);
}
.mat-mdc-tab-group.mat-tabs-with-background > .mat-mdc-tab-header .mat-mdc-tab-header-pagination-chevron,
.mat-mdc-tab-group.mat-tabs-with-background > .mat-mdc-tab-header .mat-focus-indicator::before, .mat-mdc-tab-group.mat-tabs-with-background > .mat-mdc-tab-header-pagination .mat-mdc-tab-header-pagination-chevron,
.mat-mdc-tab-group.mat-tabs-with-background > .mat-mdc-tab-header-pagination .mat-focus-indicator::before {
  border-color: var(--mat-tab-foreground-color);
}
.mat-mdc-tab-group.mat-tabs-with-background > .mat-mdc-tab-header .mat-ripple-element, .mat-mdc-tab-group.mat-tabs-with-background > .mat-mdc-tab-header .mdc-tab__ripple::before, .mat-mdc-tab-group.mat-tabs-with-background > .mat-mdc-tab-header-pagination .mat-ripple-element, .mat-mdc-tab-group.mat-tabs-with-background > .mat-mdc-tab-header-pagination .mdc-tab__ripple::before {
  background-color: var(--mat-tab-foreground-color);
}
.mat-mdc-tab-group.mat-tabs-with-background > .mat-mdc-tab-header .mat-mdc-tab-header-pagination-chevron, .mat-mdc-tab-group.mat-tabs-with-background > .mat-mdc-tab-header-pagination .mat-mdc-tab-header-pagination-chevron {
  color: var(--mat-tab-foreground-color);
}
.mat-mdc-tab-group.mat-mdc-tab-group-inverted-header {
  flex-direction: column-reverse;
}
.mat-mdc-tab-group.mat-mdc-tab-group-inverted-header .mdc-tab-indicator__content--underline {
  align-self: flex-start;
}

.mat-mdc-tab-body-wrapper {
  position: relative;
  overflow: hidden;
  display: flex;
  transition: height 500ms cubic-bezier(0.35, 0, 0.25, 1);
}
.mat-mdc-tab-body-wrapper._mat-animation-noopable {
  transition: none !important;
  animation: none !important;
}
`],encapsulation:2,changeDetection:1})}return n})(),It=class{index;tab};export{Ve as V,gt as a,ae as b,ee as c,en as e,ge as g,ve as v,yt as y};