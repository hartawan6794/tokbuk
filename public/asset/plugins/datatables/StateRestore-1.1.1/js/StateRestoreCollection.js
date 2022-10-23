var $;
var dataTable;
import StateRestore from './StateRestore';
export function setJQuery(jq) {
    $ = jq;
    dataTable = jq.fn.dataTable;
}
var StateRestoreCollection = /** @class */ (function () {
    function StateRestoreCollection(settings, opts) {
        var _this = this;
        // Check that the required version of DataTables is included
        if (!dataTable || !dataTable.versionCheck || !dataTable.versionCheck('1.10.0')) {
            throw new Error('StateRestore requires DataTables 1.10 or newer');
        }
        // Check that Select is included
        // eslint-disable-next-line no-extra-parens
        if (!dataTable.Buttons) {
            throw new Error('StateRestore requires Buttons');
        }
        var table = new dataTable.Api(settings);
        this.classes = $.extend(true, {}, StateRestoreCollection.classes);
        if (table.settings()[0]._stateRestore !== undefined) {
            return;
        }
        // Get options from user
        this.c = $.extend(true, {}, StateRestoreCollection.defaults, opts);
        this.s = {
            dt: table,
            hasColReorder: dataTable.ColReorder !== undefined,
            hasScroller: dataTable.Scroller !== undefined,
            hasSearchBuilder: dataTable.SearchBuilder !== undefined,
            hasSearchPanes: dataTable.SearchPanes !== undefined,
            hasSelect: dataTable.select !== undefined,
            states: []
        };
        this.s.dt.on('xhr', function (e, xhrsettings, json) {
            // Has staterestore been used before? Is there anything to load?
            if (json && json.stateRestore) {
                _this._addPreDefined(json.stateRestore);
            }
        });
        this.dom = {
            background: $('<div class="' + this.classes.background + '"/>'),
            closeButton: $('<div class="' + this.classes.closeButton + '">x</div>'),
            colReorderToggle: $('<div class="' + this.classes.formRow + ' ' + this.classes.checkRow + '">' +
                '<input type="checkbox" class="' +
                this.classes.colReorderToggle + ' ' +
                this.classes.checkBox +
                '" checked>' +
                '<label class="' + this.classes.checkLabel + '">' +
                this.s.dt.i18n('stateRestore.creationModal.colReorder', this.c.i18n.creationModal.colReorder) +
                '</label>' +
                '</div>'),
            columnsSearchToggle: $('<div class="' + this.classes.formRow + ' ' + this.classes.checkRow + '">' +
                '<input type="checkbox" class="' +
                this.classes.columnsSearchToggle + ' ' +
                this.classes.checkBox +
                '" checked>' +
                '<label class="' + this.classes.checkLabel + '">' +
                this.s.dt.i18n('stateRestore.creationModal.columns.search', this.c.i18n.creationModal.columns.search) +
                '</label>' +
                '</div>'),
            columnsVisibleToggle: $('<div class="' + this.classes.formRow + ' ' + this.classes.checkRow + ' ' + '">' +
                '<input type="checkbox" class="' +
                this.classes.columnsVisibleToggle + ' ' +
                this.classes.checkBox +
                '" checked>' +
                '<label class="' + this.classes.checkLabel + '">' +
                this.s.dt.i18n('stateRestore.creationModal.columns.visible', this.c.i18n.creationModal.columns.visible) +
                '</label>' +
                '</div>'),
            confirmation: $('<div class="' + this.classes.confirmation + '"/>'),
            confirmationTitleRow: $('<div class="' + this.classes.confirmationTitleRow + '"></div>'),
            createButtonRow: $('<div class="' + this.classes.formRow + ' ' + this.classes.modalFoot + '">' +
                '<button class="' + this.classes.creationButton + ' ' + this.classes.dtButton + '">' +
                this.s.dt.i18n('stateRestore.creationModal.button', this.c.i18n.creationModal.button) +
                '</button>' +
                '</div>'),
            creation: $('<div class="' + this.classes.creation + '"/>'),
            creationForm: $('<div class="' + this.classes.creationForm + '"/>'),
            creationTitle: $('<div class="' + this.classes.creationText + '">' +
                '<h2 class="' + this.classes.creationTitle + '">' +
                this.s.dt.i18n('stateRestore.creationModal.title', this.c.i18n.creationModal.title) +
                '</h2>' +
                '</div>'),
            dtContainer: $(this.s.dt.table().container()),
            duplicateError: $('<span class="' + this.classes.modalError + '">' +
                this.s.dt.i18n('stateRestore.duplicateError', this.c.i18n.duplicateError) +
                '</span>'),
            emptyError: $('<span class="' + this.classes.modalError + '">' +
                this.s.dt.i18n('stateRestore.emptyError', this.c.i18n.emptyError) +
                '</span>'),
            lengthToggle: $('<div class="' + this.classes.formRow + ' ' + this.classes.checkRow + '">' +
                '<input type="checkbox" class="' +
                this.classes.lengthToggle + ' ' +
                this.classes.checkBox +
                '" checked>' +
                '<label class="' + this.classes.checkLabel + '">' +
                this.s.dt.i18n('stateRestore.creationModal.length', this.c.i18n.creationModal.length) +
                '</label>' +
                '</div>'),
            nameInputRow: $('<div class="' + this.classes.formRow + '">' +
                '<label class="' + this.classes.nameLabel + '">' +
                this.s.dt.i18n('stateRestore.creationModal.name', this.c.i18n.creationModal.name) +
                '</label>' +
                '<input class="' + this.classes.nameInput + '" type="text">' +
                '</div>'),
            orderToggle: $('<div class="' + this.classes.formRow + ' ' + this.classes.checkRow + '">' +
                '<input type="checkbox" class="' +
                this.classes.orderToggle + ' ' +
                this.classes.checkBox +
                '" checked>' +
                '<label class="' + this.classes.checkLabel + '">' +
                this.s.dt.i18n('stateRestore.creationModal.order', this.c.i18n.creationModal.order) +
                '</label>' +
                '</div>'),
            pagingToggle: $('<div class="' + this.classes.formRow + ' ' + this.classes.checkRow + '">' +
                '<input type="checkbox" class="' +
                this.classes.pagingToggle + ' ' +
                this.classes.checkBox +
                '" checked>' +
                '<label class="' + this.classes.checkLabel + '">' +
                this.s.dt.i18n('stateRestore.creationModal.paging', this.c.i18n.creationModal.paging) +
                '</label>' +
                '</div>'),
            removeContents: $('<div class="' + this.classes.confirmationText + '"><span></span></div>'),
            removeTitle: $('<div class="' + this.classes.creationText + '">' +
                '<h2 class="' + this.classes.creationTitle + '">' +
                this.s.dt.i18n('stateRestore.removeTitle', this.c.i18n.removeTitle) +
                '</h2>' +
                '</div>'),
            scrollerToggle: $('<div class="' + this.classes.formRow + ' ' + this.classes.checkRow + '">' +
                '<input type="checkbox" class="' +
                this.classes.scrollerToggle + ' ' +
                this.classes.checkBox +
                '" checked>' +
                '<label class="' + this.classes.checkLabel + '">' +
                this.s.dt.i18n('stateRestore.creationModal.scroller', this.c.i18n.creationModal.scroller) +
                '</label>' +
                '</div>'),
            searchBuilderToggle: $('<div class="' + this.classes.formRow + ' ' + this.classes.checkRow + '">' +
                '<input type="checkbox" class="' +
                this.classes.searchBuilderToggle + ' ' +
                this.classes.checkBox +
                '" checked>' +
                '<label class="' + this.classes.checkLabel + '">' +
                this.s.dt.i18n('stateRestore.creationModal.searchBuilder', this.c.i18n.creationModal.searchBuilder) +
                '</label>' +
                '</div>'),
            searchPanesToggle: $('<div class="' + this.classes.formRow + ' ' + this.classes.checkRow + '">' +
                '<input type="checkbox" class="' +
                this.classes.searchPanesToggle + ' ' +
                this.classes.checkBox +
                '" checked>' +
                '<label class="' + this.classes.checkLabel + '">' +
                this.s.dt.i18n('stateRestore.creationModal.searchPanes', this.c.i18n.creationModal.searchPanes) +
                '</label>' +
                '</div>'),
            searchToggle: $('<div class="' + this.classes.formRow + ' ' + this.classes.checkRow + '">' +
                '<input type="checkbox" class="' +
                this.classes.searchToggle + ' ' +
                this.classes.checkBox +
                '" checked>' +
                '<label class="' + this.classes.checkLabel + '">' +
                this.s.dt.i18n('stateRestore.creationModal.search', this.c.i18n.creationModal.search) +
                '</label>' +
                '</div>'),
            selectToggle: $('<div class="' + this.classes.formRow + ' ' + this.classes.checkRow + '">' +
                '<input type="checkbox" class="' +
                this.classes.selectToggle + ' ' +
                this.classes.checkBox +
                '" checked>' +
                '<label class="' + this.classes.checkLabel + '">' +
                this.s.dt.i18n('stateRestore.creationModal.select', this.c.i18n.creationModal.select) +
                '</label>' +
                '</div>'),
            toggleLabel: $('<label class="' + this.classes.nameLabel + ' ' + this.classes.toggleLabel + '">' +
                this.s.dt.i18n('stateRestore.creationModal.toggleLabel', this.c.i18n.creationModal.toggleLabel) +
                '</label>')
        };
        table.settings()[0]._stateRestore = this;
        this._searchForStates();
        // Has staterestore been used before? Is there anything to load?
        this._addPreDefined(this.c.preDefined);
        var ajaxFunction;
        var ajaxData = {
            action: 'load'
        };
        if (typeof this.c.ajax === 'function') {
            ajaxFunction = function () {
                if (typeof _this.c.ajax === 'function') {
                    _this.c.ajax.call(_this.s.dt, ajaxData, function (s) { return _this._addPreDefined(s); });
                }
            };
        }
        else if (typeof this.c.ajax === 'string') {
            ajaxFunction = function () {
                $.ajax({
                    data: ajaxData,
                    success: function (data) {
                        _this._addPreDefined(data);
                    },
                    type: 'POST',
                    url: _this.c.ajax
                });
            };
        }
        if (typeof ajaxFunction === 'function') {
            if (this.s.dt.settings()[0]._bInitComplete) {
                ajaxFunction();
            }
            else {
                this.s.dt.one('preInit.dtsr', function () {
                    ajaxFunction();
                });
            }
        }
        this.s.dt.on('destroy.dtsr', function () {
            _this.destroy();
        });
        this.s.dt.on('draw.dtsr buttons-action.dtsr', function () { return _this.findActive(); });
        return this;
    }
    /**
     * Adds a new StateRestore instance to the collection based on the current properties of the table
     *
     * @param identifier The value that is used to identify a state.
     * @returns The state that has been created
     */
    StateRestoreCollection.prototype.addState = function (identifier, currentIdentifiers, options) {
        var _this = this;
        // If creation/saving is not allowed then return
        if (!this.c.create || !this.c.save) {
            return;
        }
        // Check if the state exists before creating a new ones
        var state = this.getState(identifier);
        var createFunction = function (id, toggles) {
            if (id.length === 0) {
                return 'empty';
            }
            else if (currentIdentifiers.includes(id)) {
                return 'duplicate';
            }
            _this.s.dt.state.save();
            var that = _this;
            var successCallback = function () {
                that.s.states.push(this);
                that._collectionRebuild();
            };
            var currState = _this.s.dt.state();
            currState.stateRestore = {
                isPredefined: false,
                state: id,
                tableId: _this.s.dt.table().node().id
            };
            if (toggles.saveState) {
                var opts = _this.c.saveState;
                // We don't want to extend, but instead AND all properties of the saveState option
                for (var _i = 0, _a = Object.keys(toggles.saveState); _i < _a.length; _i++) {
                    var key = _a[_i];
                    if (!toggles.saveState[key]) {
                        opts[key] = false;
                    }
                }
                _this.c.saveState = opts;
            }
            var newState = new StateRestore(_this.s.dt.settings()[0], $.extend(true, {}, _this.c, options), id, currState, false, successCallback);
            $(_this.s.dt.table().node()).on('dtsr-modal-inserted', function () {
                newState.dom.confirmation.one('dtsr-remove', function () { return _this._removeCallback(newState.s.identifier); });
                newState.dom.confirmation.one('dtsr-rename', function () { return _this._collectionRebuild(); });
                newState.dom.confirmation.one('dtsr-save', function () { return _this._collectionRebuild(); });
            });
            return true;
        };
        // If there isn't already a state with this identifier
        if (state === null) {
            if (this.c.creationModal || options !== undefined && options.creationModal) {
                this._creationModal(createFunction, identifier, options);
            }
            else {
                var success = createFunction(identifier, {});
                if (success === 'empty') {
                    throw new Error(this.s.dt.i18n('stateRestore.emptyError', this.c.i18n.emptyError));
                }
                else if (success === 'duplicate') {
                    throw new Error(this.s.dt.i18n('stateRestore.duplicateError', this.c.i18n.duplicateError));
                }
            }
        }
        else {
            throw new Error(this.s.dt.i18n('stateRestore.duplicateError', this.c.i18n.duplicateError));
        }
    };
    /**
     * Removes all of the states, showing a modal to the user for confirmation
     *
     * @param removeFunction The action to be taken when the action is confirmed
     */
    StateRestoreCollection.prototype.removeAll = function (removeFunction) {
        // There are no states to remove so just return
        if (this.s.states.length === 0) {
            return;
        }
        var ids = this.s.states.map(function (state) { return state.s.identifier; });
        var replacementString = ids[0];
        if (ids.length > 1) {
            replacementString = ids.slice(0, -1).join(', ') +
                this.s.dt.i18n('stateRestore.removeJoiner', this.c.i18n.removeJoiner) +
                ids.slice(-1);
        }
        $(this.dom.removeContents.children('span')).html(this.s.dt
            .i18n('stateRestore.removeConfirm', this.c.i18n.removeConfirm)
            .replace(/%s/g, replacementString));
        this._newModal(this.dom.removeTitle, this.s.dt.i18n('stateRestore.removeSubmit', this.c.i18n.removeSubmit), removeFunction, this.dom.removeContents);
    };
    /**
     * Removes all of the dom elements from the document for the collection and the stored states
     */
    StateRestoreCollection.prototype.destroy = function () {
        for (var _i = 0, _a = this.s.states; _i < _a.length; _i++) {
            var state = _a[_i];
            state.destroy();
        }
        Object.values(this.dom).forEach(function (node) {
            node.off();
            node.remove();
        });
        this.s.states = [];
        this.s.dt.off('.dtsr');
        $(this.s.dt.table().node()).off('.dtsr');
    };
    /**
     * Identifies active states and updates their button to reflect this.
     *
     * @returns An array containing objects with the details of currently active states
     */
    StateRestoreCollection.prototype.findActive = function () {
        // Make sure that the state is up to date
        this.s.dt.state.save();
        var currState = this.s.dt.state();
        // Make all of the buttons inactive so that only any that match will be marked as active
        var buttons = $('button.' + $.fn.DataTable.Buttons.defaults.dom.button.className.replace(/ /g, '.'));
        // Some of the styling libraries use a tags instead of buttons
        if (buttons.length === 0) {
            buttons = $('a.' + $.fn.DataTable.Buttons.defaults.dom.button.className.replace(/ /g, '.'));
        }
        for (var _i = 0, buttons_1 = buttons; _i < buttons_1.length; _i++) {
            var button = buttons_1[_i];
            this.s.dt.button($(button).parent()[0]).active(false);
        }
        var results = [];
        // Go through all of the states comparing if their state is the same to the current one
        for (var _a = 0, _b = this.s.states; _a < _b.length; _a++) {
            var state = _b[_a];
            if (state.compare(currState)) {
                results.push({
                    data: state.s.savedState,
                    name: state.s.identifier
                });
                // If so, find the corresponding button and mark it as active
                for (var _c = 0, buttons_2 = buttons; _c < buttons_2.length; _c++) {
                    var button = buttons_2[_c];
                    if ($(button).text() === state.s.identifier) {
                        this.s.dt.button($(button).parent()[0]).active(true);
                        break;
                    }
                }
            }
        }
        return results;
    };
    /**
     * Gets a single state that has the identifier matching that which is passed in
     *
     * @param identifier The value that is used to identify a state
     * @returns The state that has been identified or null if no states have been identified
     */
    StateRestoreCollection.prototype.getState = function (identifier) {
        for (var _i = 0, _a = this.s.states; _i < _a.length; _i++) {
            var state = _a[_i];
            if (state.s.identifier === identifier) {
                return state;
            }
        }
        return null;
    };
    /**
     * Gets an array of all of the states
     *
     * @returns Any states that have been identified
     */
    StateRestoreCollection.prototype.getStates = function (ids) {
        if (ids === undefined) {
            return this.s.states;
        }
        else {
            var states = [];
            for (var _i = 0, ids_1 = ids; _i < ids_1.length; _i++) {
                var id = ids_1[_i];
                var found = false;
                for (var _a = 0, _b = this.s.states; _a < _b.length; _a++) {
                    var state = _b[_a];
                    if (id === state.s.identifier) {
                        states.push(state);
                        found = true;
                        break;
                    }
                }
                if (!found) {
                    states.push(undefined);
                }
            }
            return states;
        }
    };
    /**
     * Reloads states that are set via datatables config or over ajax
     *
     * @param preDefined Object containing the predefined states that are to be reintroduced
     */
    StateRestoreCollection.prototype._addPreDefined = function (preDefined) {
        var _this = this;
        // There is a potential issue here if sorting where the string parts of the name are the same,
        // only the number differs and there are many states - but this wouldn't be usfeul naming so
        // more of a priority to sort alphabetically
        var states = Object.keys(preDefined).sort(function (a, b) { return a > b ? 1 : a < b ? -1 : 0; });
        var _loop_1 = function (state) {
            for (var i = 0; i < this_1.s.states.length; i++) {
                if (this_1.s.states[i].s.identifier === state) {
                    this_1.s.states.splice(i, 1);
                }
            }
            var that = this_1;
            var successCallback = function () {
                that.s.states.push(this);
                that._collectionRebuild();
            };
            var loadedState = preDefined[state];
            var newState = new StateRestore(this_1.s.dt, $.extend(true, {}, this_1.c, loadedState.c !== undefined ?
                { saveState: loadedState.c.saveState } :
                undefined, true), state, loadedState, true, successCallback);
            newState.s.savedState = loadedState;
            $(this_1.s.dt.table().node()).on('dtsr-modal-inserted', function () {
                newState.dom.confirmation.one('dtsr-remove', function () { return _this._removeCallback(newState.s.identifier); });
                newState.dom.confirmation.one('dtsr-rename', function () { return _this._collectionRebuild(); });
                newState.dom.confirmation.one('dtsr-save', function () { return _this._collectionRebuild(); });
            });
        };
        var this_1 = this;
        for (var _i = 0, states_1 = states; _i < states_1.length; _i++) {
            var state = states_1[_i];
            _loop_1(state);
        }
    };
    /**
     * Rebuilds all of the buttons in the collection of states to make sure that states and text is up to date
     */
    StateRestoreCollection.prototype._collectionRebuild = function () {
        var button = this.s.dt.button('SaveStateRestore:name');
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
        if (this.c._createInSaved) {
            stateButtons.push('createState');
        }
        var emptyText = '<span class="' + this.classes.emptyStates + '">' +
            this.s.dt.i18n('stateRestore.emptyStates', this.c.i18n.emptyStates) +
            '</span>';
        // If there are no states display an empty message
        if (this.s.states.length === 0) {
            // Don't want the empty text included more than twice
            if (!stateButtons.includes(emptyText)) {
                stateButtons.push(emptyText);
            }
        }
        else {
            // There are states to add so there shouldn't be any empty text left!
            while (stateButtons.includes(emptyText)) {
                stateButtons.splice(stateButtons.indexOf(emptyText), 1);
            }
            // There is a potential issue here if sorting where the string parts of the name are the same,
            // only the number differs and there are many states - but this wouldn't be usfeul naming so
            // more of a priority to sort alphabetically
            this.s.states = this.s.states.sort(function (a, b) {
                var aId = a.s.identifier;
                var bId = b.s.identifier;
                return aId > bId ?
                    1 :
                    aId < bId ?
                        -1 :
                        0;
            });
            // Construct the split property of each button
            for (var _i = 0, _a = this.s.states; _i < _a.length; _i++) {
                var state = _a[_i];
                var split = Object.assign([], this.c.splitSecondaries);
                if (split.includes('updateState') && (!this.c.save || !state.c.save)) {
                    split.splice(split.indexOf('updateState'), 1);
                }
                if (split.includes('renameState') &&
                    (!this.c.save || !state.c.save || !this.c.rename || !state.c.rename)) {
                    split.splice(split.indexOf('renameState'), 1);
                }
                if (split.includes('removeState') && (!this.c.remove || !state.c.remove)) {
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
        button.collectionRebuild(stateButtons);
        // Need to disable the removeAllStates button if there are no states and it is present
        var buttons = this.s.dt.buttons();
        for (var _b = 0, buttons_3 = buttons; _b < buttons_3.length; _b++) {
            var butt = buttons_3[_b];
            if ($(butt.node).hasClass('dtsr-removeAllStates')) {
                if (this.s.states.length === 0) {
                    this.s.dt.button(butt.node).disable();
                }
                else {
                    this.s.dt.button(butt.node).enable();
                }
            }
        }
    };
    /**
     * Displays a modal that is used to get information from the user to create a new state.
     *
     * @param buttonAction The action that should be taken when the button is pressed
     * @param identifier The default identifier for the next new state
     */
    StateRestoreCollection.prototype._creationModal = function (buttonAction, identifier, options) {
        var _this = this;
        this.dom.creation.empty();
        this.dom.creationForm.empty();
        this.dom.nameInputRow.children('input').val(identifier);
        this.dom.creationForm.append(this.dom.nameInputRow);
        var tableConfig = this.s.dt.settings()[0].oInit;
        var togglesToInsert = [];
        var toggleDefined = options !== undefined && options.toggle !== undefined;
        // Order toggle - check toggle and saving enabled
        if (((!toggleDefined || options.toggle.order === undefined) && this.c.toggle.order ||
            toggleDefined && options.toggle.order) &&
            this.c.saveState.order &&
            (tableConfig.ordering === undefined || tableConfig.ordering)) {
            togglesToInsert.push(this.dom.orderToggle);
        }
        // Search toggle - check toggle and saving enabled
        if (((!toggleDefined || options.toggle.search === undefined) && this.c.toggle.search ||
            toggleDefined && options.toggle.search) &&
            this.c.saveState.search &&
            (tableConfig.searching === undefined || tableConfig.searching)) {
            togglesToInsert.push(this.dom.searchToggle);
        }
        // Paging toggle - check toggle and saving enabled
        if (((!toggleDefined || options.toggle.paging === undefined) && this.c.toggle.paging ||
            toggleDefined && options.toggle.paging) &&
            this.c.saveState.paging &&
            (tableConfig.paging === undefined || tableConfig.paging)) {
            togglesToInsert.push(this.dom.pagingToggle);
        }
        // Page Length toggle - check toggle and saving enabled
        if (((!toggleDefined || options.toggle.length === undefined) && this.c.toggle.length ||
            toggleDefined && options.toggle.length) &&
            this.c.saveState.length &&
            (tableConfig.length === undefined || tableConfig.length)) {
            togglesToInsert.push(this.dom.lengthToggle);
        }
        // ColReorder toggle - check toggle and saving enabled
        if (this.s.hasColReorder &&
            ((!toggleDefined || options.toggle.colReorder === undefined) && this.c.toggle.colReorder ||
                toggleDefined && options.toggle.colReorder) &&
            this.c.saveState.colReorder) {
            togglesToInsert.push(this.dom.colReorderToggle);
        }
        // Scroller toggle - check toggle and saving enabled
        if (this.s.hasScroller &&
            ((!toggleDefined || options.toggle.scroller === undefined) && this.c.toggle.scroller ||
                toggleDefined && options.toggle.scroller) &&
            this.c.saveState.scroller) {
            togglesToInsert.push(this.dom.scrollerToggle);
        }
        // SearchBuilder toggle - check toggle and saving enabled
        if (this.s.hasSearchBuilder &&
            ((!toggleDefined || options.toggle.searchBuilder === undefined) && this.c.toggle.searchBuilder ||
                toggleDefined && options.toggle.searchBuilder) &&
            this.c.saveState.searchBuilder) {
            togglesToInsert.push(this.dom.searchBuilderToggle);
        }
        // SearchPanes toggle - check toggle and saving enabled
        if (this.s.hasSearchPanes &&
            ((!toggleDefined || options.toggle.searchPanes === undefined) && this.c.toggle.searchPanes ||
                toggleDefined && options.toggle.searchPanes) &&
            this.c.saveState.searchPanes) {
            togglesToInsert.push(this.dom.searchPanesToggle);
        }
        // Select toggle - check toggle and saving enabled
        if (this.s.hasSelect &&
            ((!toggleDefined || options.toggle.select === undefined) && this.c.toggle.select ||
                toggleDefined && options.toggle.select) &&
            this.c.saveState.select) {
            togglesToInsert.push(this.dom.selectToggle);
        }
        // Columns toggle - check toggle and saving enabled
        if (typeof this.c.toggle.columns === 'boolean' &&
            ((!toggleDefined || options.toggle.order === undefined) && this.c.toggle.columns ||
                toggleDefined && options.toggle.order) &&
            this.c.saveState.columns) {
            togglesToInsert.push(this.dom.columnsSearchToggle);
            togglesToInsert.push(this.dom.columnsVisibleToggle);
        }
        else if ((!toggleDefined || options.toggle.columns === undefined) && typeof this.c.toggle.columns !== 'boolean' ||
            typeof options.toggle.order !== 'boolean') {
            if (typeof this.c.saveState.columns !== 'boolean' && this.c.saveState.columns) {
                // Column search toggle - check toggle and saving enabled
                if ((
                // columns.search is defined when passed in
                toggleDefined &&
                    options.toggle.columns !== undefined &&
                    typeof options.toggle.columns !== 'boolean' &&
                    options.toggle.columns.search ||
                    // Columns search is not defined when passed in but is in defaults
                    (!toggleDefined ||
                        options.toggle.columns === undefined ||
                        typeof options.toggle.columns !== 'boolean' && options.toggle.columns.search === undefined) &&
                        typeof this.c.toggle.columns !== 'boolean' &&
                        this.c.toggle.columns.search) &&
                    this.c.saveState.columns.search) {
                    togglesToInsert.push(this.dom.columnsSearchToggle);
                }
                // Column visiblity toggle - check toggle and saving enabled
                if ((
                // columns.visible is defined when passed in
                toggleDefined &&
                    options.toggle.columns !== undefined &&
                    typeof options.toggle.columns !== 'boolean' &&
                    options.toggle.columns.visible ||
                    // Columns visible is not defined when passed in but is in defaults
                    (!toggleDefined ||
                        options.toggle.columns === undefined ||
                        typeof options.toggle.columns !== 'boolean' && options.toggle.columns.visible === undefined) &&
                        typeof this.c.toggle.columns !== 'boolean' &&
                        this.c.toggle.columns.visible) &&
                    this.c.saveState.columns.visible) {
                    togglesToInsert.push(this.dom.columnsVisibleToggle);
                }
            }
            else if (this.c.saveState.columns) {
                togglesToInsert.push(this.dom.columnsSearchToggle);
                togglesToInsert.push(this.dom.columnsVisibleToggle);
            }
        }
        // Make sure that the toggles are displayed alphabetically
        togglesToInsert.sort(function (a, b) {
            var aVal = a.children('label.dtsr-check-label')[0].innerHTML;
            var bVal = b.children('label.dtsr-check-label')[0].innerHTML;
            if (aVal < bVal) {
                return -1;
            }
            else if (aVal > bVal) {
                return 1;
            }
            else {
                return 0;
            }
        });
        // Append all of the toggles that are to be inserted
        for (var _i = 0, togglesToInsert_1 = togglesToInsert; _i < togglesToInsert_1.length; _i++) {
            var toggle = togglesToInsert_1[_i];
            this.dom.creationForm.append(toggle);
        }
        // Insert the toggle label next to the first check box
        $(this.dom.creationForm.children('div.' + this.classes.checkRow)[0]).prepend(this.dom.toggleLabel);
        // Insert the creation modal and the background
        this.dom.background.appendTo(this.dom.dtContainer);
        this.dom.creation
            .append(this.dom.creationTitle)
            .append(this.dom.creationForm)
            .append(this.dom.createButtonRow)
            .appendTo(this.dom.dtContainer);
        $(this.s.dt.table().node()).trigger('dtsr-modal-inserted');
        var _loop_2 = function (toggle) {
            $(toggle.children('label:last-child')).on('click', function () {
                toggle.children('input').prop('checked', !toggle.children('input').prop('checked'));
            });
        };
        // Allow the label to be clicked to toggle the checkbox
        for (var _a = 0, togglesToInsert_2 = togglesToInsert; _a < togglesToInsert_2.length; _a++) {
            var toggle = togglesToInsert_2[_a];
            _loop_2(toggle);
        }
        var creationButton = $('button.' + this.classes.creationButton.replace(/ /g, '.'));
        var inputs = this.dom.creationForm.find('input');
        // If there is an input focus on that
        if (inputs.length > 0) {
            $(inputs[0]).focus();
        }
        // Otherwise focus on the confirmation button
        else {
            creationButton.focus();
        }
        var background = $('div.' + this.classes.background.replace(/ /g, '.'));
        var keyupFunction = function (e) {
            if (e.key === 'Enter') {
                creationButton.click();
            }
            else if (e.key === 'Escape') {
                background.click();
            }
        };
        if (this.c.modalCloseButton) {
            this.dom.creation.append(this.dom.closeButton);
            this.dom.closeButton.on('click', function () { return background.click(); });
        }
        creationButton.on('click', function () {
            // Get the values of the checkBoxes
            var saveState = {
                colReorder: _this.dom.colReorderToggle.children('input').is(':checked'),
                columns: {
                    search: _this.dom.columnsSearchToggle.children('input').is(':checked'),
                    visible: _this.dom.columnsVisibleToggle.children('input').is(':checked')
                },
                length: _this.dom.lengthToggle.children('input').is(':checked'),
                order: _this.dom.orderToggle.children('input').is(':checked'),
                paging: _this.dom.pagingToggle.children('input').is(':checked'),
                scroller: _this.dom.scrollerToggle.children('input').is(':checked'),
                search: _this.dom.searchToggle.children('input').is(':checked'),
                searchBuilder: _this.dom.searchBuilderToggle.children('input').is(':checked'),
                searchPanes: _this.dom.searchPanesToggle.children('input').is(':checked'),
                select: _this.dom.selectToggle.children('input').is(':checked')
            };
            // Call the buttons functionality passing in the identifier and what should be saved
            var success = buttonAction($('input.' + _this.classes.nameInput.replace(/ /g, '.')).val(), { saveState: saveState });
            if (success === true) {
                // Remove the dom elements as operation has completed
                _this.dom.background.remove();
                _this.dom.creation.remove();
                // Unbind the keyup function  - don't want it to run unnecessarily on every keypress that occurs
                $(document).unbind('keyup', keyupFunction);
            }
            else {
                _this.dom.creation.children('.' + _this.classes.modalError).remove();
                _this.dom.creation.append(_this.dom[success + 'Error']);
            }
        });
        background.one('click', function () {
            // Remove the dome elements as operation has been cancelled
            _this.dom.background.remove();
            _this.dom.creation.remove();
            // Unbind the keyup function - don't want it to run unnecessarily on every keypress that occurs
            $(document).unbind('keyup', keyupFunction);
            // Rebuild the collection to ensure that the latest changes are present
            _this._collectionRebuild();
        });
        // Have to listen to the keyup event as `escape` doesn't trigger keypress
        $(document).on('keyup', keyupFunction);
        // Need to save the state before the focus is lost when the modal is interacted with
        this.s.dt.state.save();
    };
    /**
     * This callback is called when a state is removed.
     * This removes the state from storage and also strips it's button from the container
     *
     * @param identifier The value that is used to identify a state
     */
    StateRestoreCollection.prototype._removeCallback = function (identifier) {
        for (var i = 0; i < this.s.states.length; i++) {
            if (this.s.states[i].s.identifier === identifier) {
                this.s.states.splice(i, 1);
                i--;
            }
        }
        this._collectionRebuild();
        return true;
    };
    /**
     * Creates a new confirmation modal for the user to approve an action
     *
     * @param title The title that is to be displayed at the top of the modal
     * @param buttonText The text that is to be displayed in the confirmation button of the modal
     * @param buttonAction The action that should be taken when the confirmation button is pressed
     * @param modalContents The contents for the main body of the modal
     */
    StateRestoreCollection.prototype._newModal = function (title, buttonText, buttonAction, modalContents) {
        var _this = this;
        this.dom.background.appendTo(this.dom.dtContainer);
        this.dom.confirmationTitleRow.empty().append(title);
        var confirmationButton = $('<button class="' + this.classes.confirmationButton + ' ' + this.classes.dtButton + '">' +
            buttonText +
            '</button>');
        this.dom.confirmation
            .empty()
            .append(this.dom.confirmationTitleRow)
            .append(modalContents)
            .append($('<div class="' + this.classes.confirmationButtons + '"></div>')
            .append(confirmationButton))
            .appendTo(this.dom.dtContainer);
        $(this.s.dt.table().node()).trigger('dtsr-modal-inserted');
        var inputs = modalContents.children('input');
        // If there is an input focus on that
        if (inputs.length > 0) {
            $(inputs[0]).focus();
        }
        // Otherwise focus on the confirmation button
        else {
            confirmationButton.focus();
        }
        var background = $('div.' + this.classes.background.replace(/ /g, '.'));
        var keyupFunction = function (e) {
            // If enter same action as pressing the button
            if (e.key === 'Enter') {
                confirmationButton.click();
            }
            // If escape close modal
            else if (e.key === 'Escape') {
                background.click();
            }
        };
        // When the button is clicked, call the appropriate action,
        // remove the background and modal from the screen and unbind the keyup event.
        confirmationButton.on('click', function () {
            var success = buttonAction(true);
            if (success === true) {
                _this.dom.background.remove();
                _this.dom.confirmation.remove();
                $(document).unbind('keyup', keyupFunction);
                confirmationButton.off('click');
            }
            else {
                _this.dom.confirmation.children('.' + _this.classes.modalError).remove();
                _this.dom.confirmation.append(_this.dom[success + 'Error']);
            }
        });
        this.dom.confirmation.on('click', function (e) {
            e.stopPropagation();
        });
        // When the button is clicked, remove the background and modal from the screen and unbind the keyup event.
        background.one('click', function () {
            _this.dom.background.remove();
            _this.dom.confirmation.remove();
            $(document).unbind('keyup', keyupFunction);
        });
        $(document).on('keyup', keyupFunction);
    };
    /**
     * Private method that checks for previously created states on initialisation
     */
    StateRestoreCollection.prototype._searchForStates = function () {
        var _this = this;
        var keys = Object.keys(localStorage);
        var _loop_3 = function (key) {
            // eslint-disable-next-line no-useless-escape
            if (key.match(new RegExp('^DataTables_stateRestore_.*_' + location.pathname.replace(/\//g, '/') + '$')) ||
                key.match(new RegExp('^DataTables_stateRestore_.*_' + location.pathname.replace(/\//g, '/') +
                    '_' + this_2.s.dt.table().node().id + '$'))) {
                var loadedState_1 = JSON.parse(localStorage.getItem(key));
                if (loadedState_1.stateRestore.isPreDefined ||
                    (loadedState_1.stateRestore.tableId &&
                        loadedState_1.stateRestore.tableId !== this_2.s.dt.table().node().id)) {
                    return "continue";
                }
                var that_1 = this_2;
                var successCallback = function () {
                    this.s.savedState = loadedState_1;
                    that_1.s.states.push(this);
                    that_1._collectionRebuild();
                };
                var newState_1 = new StateRestore(this_2.s.dt, $.extend(true, {}, this_2.c, { saveState: loadedState_1.c.saveState }), loadedState_1.stateRestore.state, loadedState_1, false, successCallback);
                $(this_2.s.dt.table().node()).on('dtsr-modal-inserted', function () {
                    newState_1.dom.confirmation.one('dtsr-remove', function () { return _this._removeCallback(newState_1.s.identifier); });
                    newState_1.dom.confirmation.one('dtsr-rename', function () { return _this._collectionRebuild(); });
                    newState_1.dom.confirmation.one('dtsr-save', function () { return _this._collectionRebuild(); });
                });
            }
        };
        var this_2 = this;
        for (var _i = 0, keys_1 = keys; _i < keys_1.length; _i++) {
            var key = keys_1[_i];
            _loop_3(key);
        }
    };
    StateRestoreCollection.version = '1.0.0';
    StateRestoreCollection.classes = {
        background: 'dtsr-background',
        checkBox: 'dtsr-check-box',
        checkLabel: 'dtsr-check-label',
        checkRow: 'dtsr-check-row',
        closeButton: 'dtsr-popover-close',
        colReorderToggle: 'dtsr-colReorder-toggle',
        columnsSearchToggle: 'dtsr-columns-search-toggle',
        columnsVisibleToggle: 'dtsr-columns-visible-toggle',
        confirmation: 'dtsr-confirmation',
        confirmationButton: 'dtsr-confirmation-button',
        confirmationButtons: 'dtsr-confirmation-buttons',
        confirmationMessage: 'dtsr-confirmation-message dtsr-name-label',
        confirmationText: 'dtsr-confirmation-text',
        confirmationTitle: 'dtsr-confirmation-title',
        confirmationTitleRow: 'dtsr-confirmation-title-row',
        creation: 'dtsr-creation',
        creationButton: 'dtsr-creation-button',
        creationForm: 'dtsr-creation-form',
        creationText: 'dtsr-creation-text',
        creationTitle: 'dtsr-creation-title',
        dtButton: 'dt-button',
        emptyStates: 'dtsr-emptyStates',
        formRow: 'dtsr-form-row',
        leftSide: 'dtsr-left',
        lengthToggle: 'dtsr-length-toggle',
        modalError: 'dtsr-modal-error',
        modalFoot: 'dtsr-modal-foot',
        nameInput: 'dtsr-name-input',
        nameLabel: 'dtsr-name-label',
        orderToggle: 'dtsr-order-toggle',
        pagingToggle: 'dtsr-paging-toggle',
        rightSide: 'dtsr-right',
        scrollerToggle: 'dtsr-scroller-toggle',
        searchBuilderToggle: 'dtsr-searchBuilder-toggle',
        searchPanesToggle: 'dtsr-searchPanes-toggle',
        searchToggle: 'dtsr-search-toggle',
        selectToggle: 'dtsr-select-toggle',
        toggleLabel: 'dtsr-toggle-title'
    };
    StateRestoreCollection.defaults = {
        _createInSaved: false,
        ajax: false,
        create: true,
        creationModal: false,
        i18n: {
            creationModal: {
                button: 'Create',
                colReorder: 'Column Order',
                columns: {
                    search: 'Column Search',
                    visible: 'Column Visibility'
                },
                length: 'Page Length',
                name: 'Name:',
                order: 'Sorting',
                paging: 'Paging',
                scroller: 'Scroll Position',
                search: 'Search',
                searchBuilder: 'SearchBuilder',
                searchPanes: 'SearchPanes',
                select: 'Select',
                title: 'Create New State',
                toggleLabel: 'Includes:'
            },
            duplicateError: 'A state with this name already exists.',
            emptyError: 'Name cannot be empty.',
            emptyStates: 'No saved states',
            removeConfirm: 'Are you sure you want to remove %s?',
            removeError: 'Failed to remove state.',
            removeJoiner: ' and ',
            removeSubmit: 'Remove',
            removeTitle: 'Remove State',
            renameButton: 'Rename',
            renameLabel: 'New Name for %s:',
            renameTitle: 'Rename State'
        },
        modalCloseButton: true,
        preDefined: {},
        remove: true,
        rename: true,
        save: true,
        saveState: {
            colReorder: true,
            columns: {
                search: true,
                visible: true
            },
            length: true,
            order: true,
            paging: true,
            scroller: true,
            search: true,
            searchBuilder: true,
            searchPanes: true,
            select: true
        },
        splitSecondaries: [
            'updateState',
            'renameState',
            'removeState'
        ],
        toggle: {
            colReorder: false,
            columns: {
                search: false,
                visible: false
            },
            length: false,
            order: false,
            paging: false,
            scroller: false,
            search: false,
            searchBuilder: false,
            searchPanes: false,
            select: false
        }
    };
    return StateRestoreCollection;
}());
export default StateRestoreCollection;
