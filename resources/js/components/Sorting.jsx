import React from 'react'

class Sorting extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            'by': 'expires_at',
            'order': 'desc'
        }

        this.handleClick = this.handleClick.bind(this)
        this.sortTasks = this.sortTasks.bind(this)
    }

    componentDidMount() {
        const {by, order} = this.state

        this.sortTasks(by, order)
    }

    sortTasks(by, order) {
        let tasks = [...this.props.tasks]

        tasks.sort((a, b) => {
            const diff = new Date(b[by]) - new Date(a[by])

            return order === 'desc' ? diff : -diff
        })

        this.props.setTasks(tasks)
    }

    handleClick(e) {
        e.preventDefault()

        const by = e.target.dataset.field

        this.setState((state) => {
            let order = 'desc'
            if (by === state.by) {
                order = state.order.toLowerCase() === 'asc' ? 'desc' : 'asc'
            }

            this.sortTasks(by, order)

            return {order, by}
        })

    }

    render() {
        const {by, order} = this.state

        const arrow = order.toLowerCase() === 'asc'
            ? (<i className="fas fa-arrow-up"/>)
            : (<i className="fas fa-arrow-down"/>)

        return (
            <div className="tabs">
                <ul>
                    <li>
                        <a href="" data-field="expires_at" onClick={this.handleClick}>
                            {by === 'expires_at' && (
                                <span className="icon is-small">{arrow}</span>
                            )} Expires at
                        </a>
                    </li>
                    <li>
                        <a href="" data-field="created_at" onClick={this.handleClick}>
                            {by === 'created_at' && (
                                <span className="icon is-small">{arrow}</span>
                            )} Created at
                        </a>
                    </li>
                </ul>
            </div>
        )
    }
}

export default Sorting
