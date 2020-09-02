import React from 'react'
import axios from 'axios'

class TaskForm extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            body: '',
            expires_at: '',
            listing_id: '',
        }

        this.handleChange = this.handleChange.bind(this)
        this.handleSubmit = this.handleSubmit.bind(this)
    }

    handleChange({target}) {
        const {name, value} = target

        this.setState({[name]: value})
    }

    handleSubmit(e) {
        e.preventDefault()

        const {body, expires_at, listing_id} = this.state

        let data = {body, expires_at, listing_id}

        axios.post('/api/tasks', data).then(res => {
            if (res.status === 201) {
                this.clearForm()
                this.props.addTask(res.data)
            }
        })
    }

    clearForm() {
        this.setState({
            body: '',
            expires_at: '',
            listing_id: ''
        })
    }

    render() {
        let options = this.props.listings.map(l => (
            <option key={l.id} value={l.id}>
                {l.name}
            </option>
        ))

        return (
            <div className="card">
                <header className="card-header">
                    <p className="card-header-title">To do</p>
                </header>
                <div className="card-content">
                    <form onSubmit={this.handleSubmit}>
                        <div className="field">
                            <div className="control">
                                <textarea
                                    name="body" rows="3"
                                    value={this.state.body}
                                    onChange={this.handleChange}
                                    className="textarea is-primary" placeholder="e.g. Hello world"
                                />
                            </div>
                        </div>

                        <div className="field is-horizontal">
                            <div className="field-body">
                                <div className="control">
                                    <input
                                        type="datetime-local" name="expires_at"
                                        value={this.state.expires_at}
                                        onChange={this.handleChange}
                                    />
                                </div>
                            </div>
                            <div className="field-body">
                                <div className="field">
                                    <div className="control is-expanded">
                                        <div className="select is-fullwidth">
                                            <select
                                                name="listing_id"
                                                value={this.state.listing_id}
                                                onChange={this.handleChange}
                                            >
                                                {options}
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div className="field">
                            <div className="control">
                                <button className="button is-primary">
                                    Create
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        )
    }
}

export default TaskForm
