class AddItems {

    // 构造函数
    constructor() {
        this.VERIFY_TOKEN = 'CX^SJNi7TuJhO&w#RjTWfLvNkJvBKAuuD9NnzQIs6gbsR4S0fawknRfCj3$U2Q$a';
    }

    /**
     * 初始化
     *
     * @return void
     */
    init() {
        this.show_item_options();
        this.show_server_options();
    }

    /**
     * 绑定的事件
     *
     * @return void
     */
    bind_events() {
        this.change_role_id();
        this.click_add_item();
    }

    /**
     * 显示服务器选项
     *
     * @return void
     */
    show_server_options() {
        var that = this;
        $.ajax({
            url: '/admin/Servers/getListData',
            data: {},
            dataType: 'json',
            type: 'POST',
            success: function (result) {
                console.log(result);
                if (result.code == 200) {
                    // 容器
                    var options_html = '';

                    // 拼接
                    $.each(result.data, function (index, object) {
                        options_html += '<option value="' + object.Domain + '">' + object.Name + '</option>';
                    })

                    // 渲染
                    $('select[name="server_options"]').html(options_html);

                    // 绑定事件
                    that.change_server_option();
                }
            },
            error: function (e) {
                console.log(e);
            }
        });
    }


    /**
     * 显示物品选项
     *
     * @return void
     */
    show_item_options() {
        // 定义容器
        var options_html = '';
        $.getJSON('/admin/json/static_item_options.json', function (options) {
            // 拼接
            $.each(options, function (index, object) {
                options_html += '<option value="' + object.ItemID + '">' + object.Name + '</option>';
            });

            // 渲染
            $('select[name="static_item_options"]').append(options_html);
        });
    }

    /**
     * 显示背包选项
     *
     * @param string Domain 域名
     * @param int RoleID 角色ID
     * @return void
     */
    show_backpack_options(Domain, RoleID) {
        // 清空原有的选项
        $('select[name="backpack_options"]').html('');

        $.ajax({
            url: Domain + '/AssistSystem/getBackpackOptionDatas',
            data: {
                VerifyToken: this.VERIFY_TOKEN,
                RoleID: RoleID
            },
            dataType: 'json',
            type: 'POST',
            success: function (result) {
                if (result.Status == 100) {
                    // 定义容器
                    var options_html = '';

                    // 拼接
                    $.each(result.Data, function (index, object) {
                        options_html += '<option value="' + object.ID + '">' + object.Name + '</option>';
                    });

                    // 渲染
                    $('select[name="backpack_options"]').html(options_html);
                    $('[name="backpack_options"]').change();
                } else {
                    console.log(result);
                }
            }
        })
    }

    /**
     * 当角色ID发生改变
     *
     * @return void
     */
    change_role_id() {
        var that = this;
        $('input[name="role_id"]').unbind('change');
        $('input[name="role_id"]').on('change', function () {
            var ServerDomain = $('select[name="server_options"]').val();
            var RoleID = $(this).val();
            if (ServerDomain != '' && RoleID != '') {
                that.show_backpack_options(ServerDomain, RoleID);
            }
        });
    }

    /**
     * 当服务选项发生改变
     *
     * @return void
     */
    change_server_option() {
        var that = this;
        $('select[name="server_options"]').unbind('change');
        $('select[name="server_options"]').on('change', function () {
            var ServerDomain = $(this).val();
            var RoleID = $('input[name="role_id"]').val();
            if (ServerDomain != '' && RoleID != '') {
                that.show_backpack_options(ServerDomain, RoleID);
            }
        })
    }

    /**
     * 添加物品
     *
     * @return void
     */
    click_add_item() {
        var that = this;
        $('#add_item').on('click', function () {
            var data = {
                VerifyToken: that.VERIFY_TOKEN,
                RoleID: $('input[name="role_id"]').val(),
                PlanetID: $('select[name="backpack_options"]').val(),
                StaticItemID: $('select[name="static_item_options"]').val(),
                Quantity: $('input[name="quantity"]').val()
            };
            var ServerDomain = $('select[name="server_options"]').val();
            $.ajax({
                url: ServerDomain + '/AssistSystem/addItem',
                data: data,
                dataType: 'json',
                type: 'POST',
                success: function (result) {
                    console.log(result);
                    that.show_tips('添加成功');
                }
            });
        });
    }


    /**
     * 显示提示语
     *
     * @param string message
     * @return void
     */
    show_tips(message) {
        var html = '<div class="tips">' + message + '</div>';
        var htmlObject = $(html);
        // 渲染
        $('#tips').append(htmlObject);
        // 显示
        htmlObject.show();
        // 向下移动50像素和渐渐透明，耗时1.5秒。
        htmlObject.animate({top: "+=50px", opacity: 0}, 3000, function () {
            // 删除当前元素
            $(this).remove();
        });

        return;
    }


}

