<section class="sidebar">
  <ul class="sidebar-menu">
            {{each menu.dataList as value i}}
                {{if value.list.length == 0}}
                    <li class="{{menu.active[0]==value.id?active:''}}" name="tracking">
                        <a href="{{value.url}}">
                            <i class="{{value.icon}}"></i> 
                            <span>{{value.title}}</span>
                        </a>
                    </li>
                {{else}}
                    <li class="treeview {{menu.active[0]==value.id?active='active':active=''}}">
                        <a href="javascript:;" m-click="toggleNav">
                            <i class="{{value.icon}}"></i> 
                            <span>{{value.title}}</span>
                        </a>
                        <ul class="treeview-menu" style="display:{{if menu.active[0]==value.id}}block{{else}}none{{/if}}">
                            {{each value.list as l m}}
                                <li class="{{(menu.active[0]==value.id && menu.active[1] == l.id)?active:''}}"><a href="{{l.url}}" style="margin-left: 10px;"><i class="fa fa-angle-double-right"></i> {{l.title}}</a></li>
                            {{/each}}
                        </ul>
                    </li>
                {{/if}}   
            {{/each}}
        </ul>
  </section>