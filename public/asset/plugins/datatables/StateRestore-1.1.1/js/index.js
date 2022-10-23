/*! StateRestore 1.1.1
 * 2019-2022 SpryMedia Ltd - datatables.net/license
 */
import StateRestore, { setJQuery as stateRestoreJQuery } from './StateRestore';
import StateRestoreCollection, { setJQuery as stateRestoreCollectionJQuery } from './StateRestoreCollection';
// DataTables extensions common UMD. Note that this allows for AMD, CommonJS
// (with window and jQuery being allowed as parameters to the returned
// function) or just default browser loading.
(function (factory) {
    if (typeof define === 'function' && define.amd) {
        // AMD
        define(['jquery', 'datatables.net'], function ($) {
            return factory($, window, document);
        });
    }
    else if (typeof exports === 'object') {
        // CommonJS
        module.exports = function (root, $) {
            if (!root) {
                root = window;
            }
            if (!$ || !$.fn.dataTable) {
                // eslint-disable-next-line @typescript-eslint/no-var-requires
                $ = require('datatables.net')(root, $).$;
            }
            return factory($, root, root.document);
        };
    }
    else {
        // Browser - assume jQuery has already been loaded
        factory(window.jQuery, window, document);
    }
}(function ($, window, document) {
    stateRestoreJQuery($);
    stateRestoreCollectionJQuery($);
    var dataTable = $.fn.dataTable;
    $.fn.dataTable.StateRestore = StateRestore;
    $.fn.DataTable.StateRestore = StateRestore;
    $.fn.dataTable.StateRestoreCollection = StateRestoreCollection;
    $.fn.DataTable.StateRestoreCollection = StateRestoreCollection;
    var apiRegister = $.fn.dataTable.Api.register;
    apiRegister('stateRestore()', function () {
        return this;
    });
    apiRegister('stateRestore.state()', function (identifier) {
        var ctx = this.context[0];
        if (!ctx._stateRestore) {
            var api = $.fn.DataTable.Api(ctx);
            var src = new $.fn.dataTable.StateRestoreCollection(api, {});
            _stateRegen(api, src);
        }
        this[0] = ctx._stateRestore.getState(identifier);
        return this;
    });
    apiRegister('stateRestore.state.add()', function (identifier, options) {
        var ctx = this.context[0];
        if (!ctx._stateRestore) {
            var api = $.fn.DataTable.Api(ctx);
            var src = new $.fn.dataTable.StateRestoreCollection(api, {});
            _stateRegen(api, src);
        }
        if (!ctx._stateRestore.c.create) {
            return this;
        }
        if (ctx._stateRestore.addState) {
            var states = ctx._stateRestore.s.states;
            var ids = [];
            for (var _i = 0, states_1 = states; _i < states_1.length; _i++) {
                var intState = states_1[_i];
                ids.push(intState.s.identifier);
            }
            ctx._stateRestore.addState(identifier, ids, options);
            return this;
        }
    });
    apiRegister('stateRestore.states()', function (ids) {
        var ctx = this.context[0];
        if (!ctx._stateRestore) {
            var api = $.fn.DataTable.Api(ctx);
            var src = new $.fn.dataTable.StateRestoreCollection(api, {});
            _stateRegen(api, src);
        }
        this.length = 0;
        this.push.apply(this, ctx._stateRestore.getStates(ids));
        return this;
    });
    apiRegister('stateRestore.state().save()', function () {
        var ctx = this[0];
        // Check if saving states is allowed
        if (ctx.c.save) {
            ctx.save();
        }
        return this;
    });
    apiRegister('stateRestore.state().rename()', function (newIdentifier) {
        var ctx = this.context[0];
        var state = this[0];
        // Check if renaming states is allowed
        if (state.c.save) {
            var states = ctx._stateRestore.s.states;
            var ids = [];
            for (var _i = 0, states_2 = states; _i < states_2.length; _i++) {
                var intState = states_2[_i];
                ids.push(intState.s.identifier);
            }
            state.rename(newIdentifier, ids);
        }
        return this;
    });
    apiRegister('stateRestore.state().load()', function () {
        var ctx = this[0];
        ctx.load();
        return this;
    });
    apiRegister('stateRestore.state().remove()', function (skipModal) {
        var ctx = this[0];
        // Check if removal of states is allowed
        if (ctx.c.remove) {
            ctx.remove(skipModal);
        }
        return this;
    });
    apiRegister('stateRestore.states().remove()', function (skipModal) {
        var _this = this;
        var removeAllCallBack = function (skipModalIn) {
            var success = true;
            var that = _this.toArray();
            while (that.length > 0) {
                var set = that[0];
                if (set !== undefined && set.c.remove) {
                    var tempSuccess = set.remove(skipModalIn);
                    if (tempSuccess !== true) {
                        success = tempSuccess;
                    }
                    else {
                        that.splice(0, 1);
                    }
                }
                else {
                    break;
                }
            }
            return success;
        };
        if (this.context[0]._stateRestore.c.remove) {
            if (skipModal) {
                removeAllCallBack(skipModal);
            }
            else {
                this.context[0]._stateRestore.removeAll(removeAllCallBack);
            }
        }
        return this;
    });
    apiRegister('stateRestore.activeStates()', function () {
        var ctx = this.context[0];
        this.length = 0;
        if (!ctx._stateRestore) {
            var api = $.fn.DataTable.Api(ctx);
            var src = new $.fn.dataTable.StateRestoreCollection(api, {});
            _stateRegen(api, src);
        }
        if (ctx._stateRestore) {
            this.push.apply(this, ctx._stateRestore.findActive());
        }
        return this;
    });
    $.fn.dataTable.ext.buttons.stateRestore = {
        action: function (e, dt, node, config) {
            config._stateRestore.load();
            node.blur();
        },
        config: {
            split: ['updateState', 'renameState', 'removeState']
        },
        text: function (dt) {
            return dt.i18n('buttons.stateRestore', 'State %d', dt.stateRestore.states()[0].length + 1);
        }
    };
    $.fn.dataTable.ext.buttons.updateState = {
        action: function (e, dt, node, config) {
            $('div.dt-button-background').click();
            config.parent._stateRestore.save();
        },
        text: function (dt) {
            return dt.i18n('buttons.updateState', 'Update');
        }
    };
    $.fn.dataTable.ext.buttons.savedStates = {
        buttons: [],
        extend: 'collection',
        init: function (dt, node, config) {
            dt.on('stateRestore-change', function () {
                dt.button(node).text(dt.i18n('buttons.savedStates', 'Saved States', dt.stateRestore.states().length));
            });
            if (dt.settings()[0]._stateRestore === undefined) {
                _buttonInit(dt, config);
            }
        },
        name: 'SaveStateRestore',
        text: function (dt) {
            return dt.i18n('buttons.savedStates', 'Saved States', 0);
        }
    };
    $.fn.dataTable.ext.buttons.savedStatesCreate = {
        buttons: [],
        extend: 'collection',
        init: function (dt, node, config) {
            dt.on('stateRestore-change', function () {
                dt.button(node).text(dt.i18n('buttons.savedStates', 'Saved States', dt.stateRestore.states().length));
            });
            if (dt.settings()[0]._stateRestore === undefined) {
                if (config.config === undefined) {
                    config.config = {};
                }
                config.config._createInSaved = true;
                _buttonInit(dt, config);
            }
        },
        name: 'SaveStateRestore',
        text: function (dt) {
            return dt.i18n('buttons.savedStates', 'Saved States', 0);
        }
    };
    $.fn.dataTable.ext.buttons.createState = {
        action: function (e, dt, node, config) {
            e.stopPropagation();
            var stateRestoreOpts = dt.settings()[0]._stateRestore.c;
            var language = dt.settings()[0].oLanguage;
            // If creation/saving is not allowed then return
            if (!stateRestoreOpts.create || !stateRestoreOpts.save) {
                return;
            }
            var prevStates = dt.stateRestore.states().toArray();
            // Create a replacement regex based on the i18n values
            var defaultString = language.buttons !== undefined && language.buttons.stateRestore !== undefined ?
                language.buttons.stateRestore :
                'State ';
            var replaceRegex;
            if (defaultString.indexOf('%d') === defaultString.length - 3) {
                replaceRegex = new RegExp(defaultString.replace(/%d/g, ''));
            }
            else {
                var splitString = defaultString.split('%d');
                replaceRegex = [];
                for (var _i = 0, splitString_1 = splitString; _i < splitString_1.length; _i++) {
                    var split = splitString_1[_i];
                    replaceRegex.push(new RegExp(split));
                }
            }
            var getId = function (identifier) {
                var id;
                if (Array.isArray(replaceRegex)) {
                    id = identifier;
                    for (var _i = 0, replaceRegex_1 = replaceRegex; _i < replaceRegex_1.length; _i++) {
                        var reg = replaceRegex_1[_i];
                        id = id.replace(reg, '');
                    }
                }
                else {
                    id = identifier.replace(replaceRegex, '');
                }
                // If the id after replacement is not a number, or the length is the same as before,
                //  it has been customised so return 0
                if (isNaN(+id) || id.length === identifier) {
                    return 0;
                }
                // Otherwise return the number that has been assigned previously
                else {
                    return +id;
                }
            };
            // Extract the numbers from the identifiers that use the standard naming convention
            var identifiers = prevStates
                .map(function (state) { return getId(state.s.identifier); })
                .sort(function (a, b) { return +a < +b ?
                1 :
                +a > +b ?
                    -1 :
                    0; });
            var lastNumber = identifiers[0];
            dt.stateRestore.state.add(dt.i18n('buttons.stateRestore', 'State %d', lastNumber !== undefined ? lastNumber + 1 : 1), config.config);
            var states = dt.stateRestore.states().sort(function (a, b) {
                var aId = +getId(a.s.identifier);
                var bId = +getId(b.s.identifier);
                return aId > bId ?
                    1 :
                    aId < bId ?
                        -1 :
                        0;
            });
            var button = dt.button('SaveStateRestore:name');
            var stateButtons = button[0] !== undefined && button[0].inst.c.buttons[0].buttons !== undefined ?
                button[0].inst.c.buttons[0].buttons :
                [];
            // remove any states from the previous rebuild - if they are still there they will be added later
            for (var i = 0; i < stateButtons.length; i++) {
                if (stateButtons[i].extend === 'stateRestore') {
                    stateButtons.splice(i, 1);
                    i--;
                }
            }
            if (stateRestoreOpts._createInSaved) {
                stateButtons.push('createState');
                stateButtons.push('');
            }
            for (var _a = 0, states_3 = states; _a < states_3.length; _a++) {
                var state = states_3[_a];
                var split = Object.assign([], stateRestoreOpts.splitSecondaries);
                if (split.includes('updateState') && !stateRestoreOpts.save) {
                    split.splice(split.indexOf('updateState'), 1);
                }
                if (split.includes('renameState') &&
                    (!stateRestoreOpts.save || !stateRestoreOpts.rename)) {
                    split.splice(split.indexOf('renameState'), 1);
                }
                if (split.includes('removeState') && !stateRestoreOpts.remove) {
                    split.splice(split.indexOf('removeState'), 1);
                }
                if (split.length > 0 &&
                    !split.includes('<h3>' + state.s.identifier + '</h3>')) {
                    split.unshift('<h3>' + state.s.identifier + '</h3>');
                }
                stateButtons.push({
                    _stateRestore: state,
                    attr: {
                        title: state.s.identifier
                    },
                    config: {
                        split: split
                    },
                    extend: 'stateRestore',
                    text: state.s.identifier
                });
            }
            dt.button('SaveStateRestore:name').collectionRebuild(stateButtons);
            node.blur();
            // Need to disable the removeAllStates button if there are no states and it is present
            var buttons = dt.buttons();
            for (var _b = 0, buttons_1 = buttons; _b < buttons_1.length; _b++) {
                var butt = buttons_1[_b];
                if ($(butt.node).hasClass('dtsr-removeAllStates')) {
                    if (states.length === 0) {
                        dt.button(butt.node).disable();
                    }
                    else {
                        dt.button(butt.node).enable();
                    }
                }
            }
        },
        init: function (dt, node, config) {
            if (dt.settings()[0]._stateRestore === undefined && dt.button('SaveStateRestore:name').length > 1) {
                _buttonInit(dt, config);
            }
        },
        text: function (dt) {
            return dt.i18n('buttons.createState', 'Create State');
        }
    };
    $.fn.dataTable.ext.buttons.removeState = {
        action: function (e, dt, node, config) {
            config.parent._stateRestore.remove();
            node.blur();
        },
        text: function (dt) {
            return dt.i18n('buttons.removeState', 'Remove');
        }
    };
    $.fn.dataTable.ext.buttons.removeAllStates = {
        action: function (e, dt, node) {
            dt.stateRestore.states().remove(true);
            node.blur();
        },
        className: 'dt-button dtsr-removeAllStates',
        init: function (dt, node) {
            if (dt.stateRestore.states().length === 0) {
                $(node).addClass('disabled');
            }
        },
        text: function (dt) {
            return dt.i18n('buttons.removeAllStates', 'Remove All States');
        }
    };
    $.fn.dataTable.ext.buttons.renameState = {
        action: function (e, dt, node, config) {
            var states = dt.settings()[0]._stateRestore.s.states;
            var ids = [];
            for (var _i = 0, states_4 = states; _i < states_4.length; _i++) {
                var state = states_4[_i];
                ids.push(state.s.identifier);
            }
            config.parent._stateRestore.rename(undefined, ids);
            node.blur();
        },
        text: function (dt) {
            return dt.i18n('buttons.renameState', 'Rename');
        }
    };
    function _init(settings, options) {
        if (options === void 0) { options = null; }
        var api = new dataTable.Api(settings);
        var opts = options
            ? options
            : api.init().stateRestore || dataTable.defaults.stateRestore;
        var stateRestore = new StateRestoreCollection(api, opts);
        _stateRegen(api, stateRestore);
        return stateRestore;
    }
    /**
     * Initialisation function if initialising using a button
     *
     * @param dt The datatables instance
     * @param config the config for the button
     */
    function _buttonInit(dt, config) {
        var SRC = new $.fn.dataTable.StateRestoreCollection(dt, config.config);
        _stateRegen(dt, SRC);
    }
    function _stateRegen(dt, src) {
        var states = dt.stateRestore.states();
        var button = dt.button('SaveStateRestore:name');
        var stateButtons = button[0] !== undefined && button[0].inst.c.buttons[0].buttons !== undefined ?
            button[0].inst.c.buttons[0].buttons :
            [];
        var stateRestoreOpts = dt.settings()[0]._stateRestore.c;
        // remove any states from the previous rebuild - if they are still there they will be added later
        for (var i = 0; i < stateButtons.length; i++) {
            if (stateButtons[i].extend === 'stateRestore') {
                stateButtons.splice(i, 1);
                i--;
            }
        }
        if (stateRestoreOpts._createInSaved) {
            stateButtons.push('createState');
        }
        if (states === undefined || states.length === 0) {
            stateButtons.push('<span class="' + src.classes.emptyStates + '">' +
                dt.i18n('stateRestore.emptyStates', src.c.i18n.emptyStates) +
                '</span>');
        }
        else {
            for (var _i = 0, states_5 = states; _i < states_5.length; _i++) {
                var state = states_5[_i];
                var split = Object.assign([], stateRestoreOpts.splitSecondaries);
                if (split.includes('updateState') && !stateRestoreOpts.save) {
                    split.splice(split.indexOf('updateState'), 1);
                }
                if (split.includes('renameState') &&
                    (!stateRestoreOpts.save || !stateRestoreOpts.rename)) {
                    split.splice(split.indexOf('renameState'), 1);
                }
                if (split.includes('removeState') && !stateRestoreOpts.remove) {
                    split.splice(split.indexOf('removeState'), 1);
                }
                if (split.length > 0 &&
                    !split.includes('<h3>' + state.s.identifier + '</h3>')) {
                    split.unshift('<h3>' + state.s.identifier + '</h3>');
                }
                stateButtons.push({
                    _stateRestore: state,
                    attr: {
                        title: state.s.identifier
                    },
                    config: {
                        split: split
                    },
                    extend: 'stateRestore',
                    text: state.s.identifier
                });
            }
        }
        dt.button('SaveStateRestore:name').collectionRebuild(stateButtons);
        // Need to disable the removeAllStates button if there are no states and it is present
        var buttons = dt.buttons();
        for (var _a = 0, buttons_2 = buttons; _a < buttons_2.length; _a++) {
            var butt = buttons_2[_a];
            if ($(butt.node).hasClass('dtsr-removeAllStates')) {
                if (states.length === 0) {
                    dt.button(butt.node).disable();
                }
                else {
                    dt.button(butt.node).enable();
                }
            }
        }
    }
    // Attach a listener to the document which listens for DataTables initialisation
    // events so we can automatically initialise
    $(document).on('preInit.dt.dtsr', function (e, settings) {
        if (e.namespace !== 'dt') {
            return;
        }
        if (settings.oInit.stateRestore ||
            dataTable.defaults.stateRestore) {
            if (!settings._stateRestore) {
                _init(settings, null);
            }
        }
    });
}));
