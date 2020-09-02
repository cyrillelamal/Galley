import React from 'react'
import moment from "moment";

class Task extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            edit: false
        }

        this.handleClick = this.handleClick.bind(this)
    }

    handleClick(e) {
        e.preventDefault()

        const action = e.target.dataset.action

        if (action === 'delete') {
            this.props.removeTask(this.props.task)
        } else if (action === 'update') {

        }
    }

    render() {
        const {expires_at} = this.props.task
        const expiresAt = expires_at ? moment(expires_at) : ''

        return (
            <div className="card my-1">
                <div className="card-content">
                    <div className="content">
                        {this.props.task.body}
                    </div>
                </div>
                <footer className="card-footer">
                    {expiresAt && (
                        <div className="card-footer-item has-text-danger">
                            {expiresAt.format('DD/MM/YYYY HH:mm')}
                        </div>
                    )}
                    <div className="card-footer-item">
                        <button
                            data-action='update'
                            className="button is-warning"
                            onClick={this.handleClick}
                        >
                            Edit
                        </button>
                    </div>
                    <div className="card-footer-item">
                        <button
                            data-action='delete'
                            className="button is-success"
                            onClick={this.handleClick}
                        >
                            Finish
                        </button>
                    </div>
                </footer>
            </div>
        )
    }
}

export default Task
